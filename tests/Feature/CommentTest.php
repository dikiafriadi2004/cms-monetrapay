<?php

use App\Models\Blog;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;

uses(RefreshDatabase::class);

beforeEach(function () {
    RateLimiter::clear('comment:127.0.0.1');
});

describe('Comment submission', function () {
    it('creates an approved comment on valid submit', function () {
        $user = User::factory()->create();
        $blog = Blog::factory()->create([
            'user_id' => $user->id,
            'slug' => 'my-post',
            'status' => 'published',
        ]);

        $response = $this->post(route('comments.store', $blog), [
            '_t' => time() - 5,
            'author_name' => 'Andi',
            'author_email' => 'andi@example.com',
            'content' => 'Artikel yang sangat bagus dan informatif!',
        ]);

        $response->assertSessionHas('comment_success');
        expect(Comment::count())->toBe(1);
        expect(Comment::first()->status)->toBe('approved');
    });

    it('rejects honeypot bot', function () {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id, 'slug' => 'p1', 'status' => 'published']);

        $this->post(route('comments.store', $blog), [
            '_t' => time() - 5,
            'author_name' => 'Bot',
            'author_email' => 'bot@example.com',
            'content' => 'spam',
            'website' => 'evil.com',
        ]);

        expect(Comment::count())->toBe(0);
    });

    it('rejects too-fast submit (bot)', function () {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id, 'slug' => 'p2', 'status' => 'published']);

        $this->post(route('comments.store', $blog), [
            '_t' => time(), // 0 sec elapsed
            'author_name' => 'Bot',
            'author_email' => 'b@b.com',
            'content' => 'spam content',
        ]);

        expect(Comment::count())->toBe(0);
    });

    it('marks comment with too many links as spam', function () {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id, 'slug' => 'p3', 'status' => 'published']);

        $this->post(route('comments.store', $blog), [
            '_t' => time() - 5,
            'author_name' => 'Spammer',
            'author_email' => 's@s.com',
            'content' => 'Check https://a.com and https://b.com and https://c.com and https://d.com',
        ]);

        expect(Comment::count())->toBe(1);
        expect(Comment::first()->status)->toBe('spam');
    });

    it('rejects duplicate comment within 10 minutes', function () {
        $user = User::factory()->create();
        $blog = Blog::factory()->create([
            'user_id' => $user->id,
            'slug' => 'dup-post',
            'status' => 'published',
        ]);

        $payload = [
            '_t' => time() - 5,
            'author_name' => 'Andi',
            'author_email' => 'andi@example.com',
            'content' => 'Artikel sangat membantu sekali.',
        ];

        $this->post(route('comments.store', $blog), $payload);
        expect(Comment::count())->toBe(1);

        // Same email + content → blocked
        $response = $this->post(route('comments.store', $blog), $payload);
        $response->assertSessionHas('comment_error');
        expect(Comment::count())->toBe(1);
    });

    it('does not allow comments on draft blog', function () {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id, 'slug' => 'draft-post', 'status' => 'draft']);

        $response = $this->post(route('comments.store', $blog), [
            '_t' => time() - 5,
            'author_name' => 'Andi',
            'author_email' => 'a@a.com',
            'content' => 'good content here',
        ]);

        $response->assertStatus(404);
        expect(Comment::count())->toBe(0);
    });

    it('validates required fields', function () {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id, 'slug' => 'p4', 'status' => 'published']);

        $response = $this->post(route('comments.store', $blog), [
            '_t' => time() - 5,
            'author_name' => 'A',  // too short
            'author_email' => 'not-email',
            'content' => 'hi',  // too short
        ]);

        $response->assertSessionHasErrors(['author_name', 'author_email', 'content']);
    });

    it('rejects parent comment from different blog', function () {
        $user = User::factory()->create();
        $blog1 = Blog::factory()->create(['user_id' => $user->id, 'slug' => 'b1', 'status' => 'published']);
        $blog2 = Blog::factory()->create(['user_id' => $user->id, 'slug' => 'b2', 'status' => 'published']);

        $parent = Comment::create([
            'blog_id' => $blog1->id,
            'author_name' => 'X', 'author_email' => 'x@x.com',
            'content' => 'parent on blog1', 'status' => 'approved',
        ]);

        $response = $this->post(route('comments.store', $blog2), [
            '_t' => time() - 5,
            'author_name' => 'Andi',
            'author_email' => 'a@a.com',
            'content' => 'reply attempted on wrong blog',
            'parent_id' => $parent->id,
        ]);

        $response->assertStatus(403);
    });
});

describe('Approved comments display', function () {
    it('shows only approved comments to public', function () {
        $user = User::factory()->create();
        $blog = Blog::factory()->create([
            'user_id' => $user->id,
            'slug' => 'view-post',
            'status' => 'published',
        ]);

        Comment::create([
            'blog_id' => $blog->id,
            'author_name' => 'Approved User',
            'author_email' => 'a@a.com',
            'content' => 'This is APPROVED-COMMENT-X',
            'status' => 'approved',
        ]);
        Comment::create([
            'blog_id' => $blog->id,
            'author_name' => 'Pending User',
            'author_email' => 'p@p.com',
            'content' => 'This is PENDING-COMMENT-Y',
            'status' => 'pending',
        ]);
        Comment::create([
            'blog_id' => $blog->id,
            'author_name' => 'Spam User',
            'author_email' => 's@s.com',
            'content' => 'This is SPAM-COMMENT-Z',
            'status' => 'spam',
        ]);

        $response = $this->get('/blog/view-post');

        $response->assertStatus(200);
        $response->assertSee('APPROVED-COMMENT-X', false);
        $response->assertDontSee('PENDING-COMMENT-Y', false);
        $response->assertDontSee('SPAM-COMMENT-Z', false);
    });
});
