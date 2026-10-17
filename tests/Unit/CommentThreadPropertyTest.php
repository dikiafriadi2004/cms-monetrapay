<?php

use App\Models\Blog;
use App\Models\Comment;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

/**
 * Helper: create a random comment tree of given size.
 * Returns the root comment and total count of comments created.
 */
function createRandomCommentTree(Blog $blog, int $totalCount, ?int $parentId = null, int $depth = 1, int $maxDepth = 5): array
{
    if ($totalCount <= 0 || $depth > $maxDepth) {
        return [];
    }

    $created = [];
    $remaining = $totalCount;

    while ($remaining > 0) {
        $hasChildren = $depth < $maxDepth && random_int(0, 2) > 0;
        $children = [];

        $comment = Comment::create([
            'blog_id' => $blog->id,
            'parent_id' => $parentId,
            'author_name' => "User {$depth}.{$remaining}",
            'author_email' => "u{$depth}{$remaining}@example.com",
            'content' => "Random comment at depth {$depth}",
            'status' => 'approved',
        ]);
        $created[] = $comment->id;
        $remaining--;

        if ($hasChildren && $remaining > 0) {
            $childBudget = random_int(1, min(3, $remaining));
            $childIds = createRandomCommentTree($blog, $childBudget, $comment->id, $depth + 1, $maxDepth);
            $created = array_merge($created, $childIds);
            $remaining -= count($childIds);
        }
    }

    return $created;
}

describe('Comment thread invariants', function () {

    /**
     * Property: every comment's parent (if any) must belong to the same blog.
     */
    it('parent and child always share the same blog', function () {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id, 'status' => 'published']);

        createRandomCommentTree($blog, 20);

        $comments = Comment::all();
        foreach ($comments as $c) {
            if ($c->parent_id !== null) {
                $parent = Comment::find($c->parent_id);
                expect($parent)->not->toBeNull();
                expect($parent->blog_id)->toBe($c->blog_id);
            } else {
                expect($c->parent_id)->toBeNull();
            }
        }
    })->repeat(10);

    /**
     * Property: parent-child cascade — deleting a parent removes all descendants.
     */
    it('cascade deletes descendants when parent removed', function () {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id, 'status' => 'published']);

        // Create thread: root → child → grandchild
        $root = Comment::create([
            'blog_id' => $blog->id, 'author_name' => 'R', 'author_email' => 'r@r.com',
            'content' => 'root', 'status' => 'approved',
        ]);
        $child = Comment::create([
            'blog_id' => $blog->id, 'parent_id' => $root->id, 'author_name' => 'C',
            'author_email' => 'c@c.com', 'content' => 'child', 'status' => 'approved',
        ]);
        $grandchild = Comment::create([
            'blog_id' => $blog->id, 'parent_id' => $child->id, 'author_name' => 'G',
            'author_email' => 'g@g.com', 'content' => 'gc', 'status' => 'approved',
        ]);

        expect(Comment::count())->toBe(3);
        $root->delete();
        expect(Comment::count())->toBe(0);
    });

    /**
     * Property: deleting blog cascades to all its comments.
     */
    it('cascade deletes all comments when blog removed', function () {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id, 'status' => 'published']);

        createRandomCommentTree($blog, 15);
        expect(Comment::where('blog_id', $blog->id)->count())->toBeGreaterThan(0);

        $blog->forceDelete();

        expect(Comment::where('blog_id', $blog->id)->count())->toBe(0);
    })->repeat(5);

    /**
     * Property: approved scope returns only approved comments.
     */
    it('approved scope filters correctly', function () {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id, 'status' => 'published']);

        $statuses = ['pending', 'approved', 'spam', 'trash'];
        for ($i = 0; $i < 40; $i++) {
            Comment::create([
                'blog_id' => $blog->id,
                'author_name' => "U{$i}", 'author_email' => "u{$i}@x.com",
                'content' => "comment {$i}",
                'status' => $statuses[array_rand($statuses)],
            ]);
        }

        $approved = Comment::approved()->get();
        $expected = Comment::where('status', 'approved')->count();

        expect($approved->count())->toBe($expected);
        foreach ($approved as $c) {
            expect($c->status)->toBe('approved');
        }
    })->repeat(5);

    /**
     * Property: gravatar URL is deterministic — same email always gives same hash.
     */
    it('gravatar URL is deterministic per email', function () {
        $email = 'user@example.com';
        $hash = md5(strtolower(trim($email)));

        for ($i = 0; $i < 5; $i++) {
            $c = new Comment(['author_email' => $email]);
            $url = $c->gravatarUrl(64);
            expect($url)->toContain($hash);
            expect($url)->toContain('s=64');
        }
    });

    /**
     * Property: gravatar URL normalizes case and whitespace.
     */
    it('gravatar URL normalizes email case and whitespace', function (string $email) {
        $expected = md5(strtolower(trim($email)));

        $c = new Comment(['author_email' => $email]);
        $url = $c->gravatarUrl();

        expect($url)->toContain($expected);
    })->with([
        '  user@example.com  ',
        'USER@EXAMPLE.COM',
        'User@Example.Com',
        'user@example.com',
        "\tuser@example.com\n",
    ]);
});

describe('Newsletter token property tests', function () {
    it('generates unique random tokens of 48 chars', function () {
        $tokens = [];
        for ($i = 0; $i < 30; $i++) {
            $token = NewsletterSubscriber::generateToken();
            expect(strlen($token))->toBe(48);
            expect($tokens)->not->toContain($token);
            $tokens[] = $token;
        }
    });

    it('token contains only alphanumeric chars', function () {
        for ($i = 0; $i < 20; $i++) {
            $token = NewsletterSubscriber::generateToken();
            expect($token)->toMatch('/^[A-Za-z0-9]+$/');
        }
    });
});
