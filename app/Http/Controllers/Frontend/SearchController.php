<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Page;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    /**
     * Search across blogs and pages with simple relevance ranking.
     * Title matches rank higher than content matches; published_at recency
     * acts as tiebreaker.
     */
    public function index(Request $request)
    {
        $query = trim((string) $request->input('q', ''));
        $type = $request->input('type', 'all'); // all|blog|page
        $results = collect();

        if (mb_strlen($query) < 3) {
            return view('frontend.search', [
                'query' => $query,
                'type' => $type,
                'results' => $results,
                'total' => 0,
            ]);
        }

        $like = '%'.$query.'%';

        if (in_array($type, ['all', 'blog'], true)) {
            $blogs = Blog::where('status', 'published')
                ->with(['author', 'categories'])
                ->where(function ($q) use ($like) {
                    $q->where('title', 'like', $like)
                        ->orWhere('excerpt', 'like', $like)
                        ->orWhere('content', 'like', $like);
                })
                ->limit(50)
                ->get()
                ->map(function (Blog $blog) use ($query) {
                    return (object) [
                        'type' => 'blog',
                        'title' => $blog->title,
                        'slug' => $blog->slug,
                        'url' => route('blog.show', $blog->slug),
                        'image' => $blog->featured_image,
                        'date' => $blog->published_at ?? $blog->created_at,
                        'author' => $blog->author instanceof User ? $blog->author->name : null,
                        'categories' => $blog->categories->pluck('name')->all(),
                        'snippet' => $this->makeSnippet($blog->excerpt ?: strip_tags($blog->content), $query),
                        'score' => $this->scoreItem($blog->title, $blog->excerpt ?: strip_tags($blog->content), $query, $blog->published_at),
                    ];
                });

            $results = $results->concat($blogs);
        }

        if (in_array($type, ['all', 'page'], true)) {
            $pages = Page::where('status', 'published')
                ->where(function ($q) use ($like) {
                    $q->where('title', 'like', $like)
                        ->orWhere('content', 'like', $like);
                })
                ->limit(50)
                ->get()
                ->map(function (Page $page) use ($query) {
                    return (object) [
                        'type' => 'page',
                        'title' => $page->title,
                        'slug' => $page->slug,
                        'url' => url('/'.$page->slug),
                        'image' => $page->featured_image,
                        'date' => $page->updated_at,
                        'author' => null,
                        'categories' => [],
                        'snippet' => $this->makeSnippet(strip_tags((string) $page->content), $query),
                        'score' => $this->scoreItem($page->title, strip_tags((string) $page->content), $query, $page->updated_at),
                    ];
                });

            $results = $results->concat($pages);
        }

        // Sort by score desc, then date desc
        $results = $results->sortByDesc(fn ($r) => [$r->score, optional($r->date)->timestamp ?? 0])->values();

        // Paginate in-memory (20 per page)
        $page = max(1, (int) $request->input('page', 1));
        $perPage = 20;
        $total = $results->count();
        $paginatedResults = $results->slice(($page - 1) * $perPage, $perPage)->values();

        $paginator = new LengthAwarePaginator(
            $paginatedResults,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->except('page')]
        );

        return view('frontend.search', [
            'query' => $query,
            'type' => $type,
            'results' => $paginatedResults,
            'total' => $total,
            'paginator' => $paginator,
        ]);
    }

    /**
     * Score an item: 100 for exact title match, 50 for title contains,
     * 10 for content contains, +occurrence count.
     */
    protected function scoreItem(string $title, string $content, string $query, $date = null): int
    {
        $score = 0;
        $titleLower = mb_strtolower($title);
        $contentLower = mb_strtolower($content);
        $q = mb_strtolower($query);

        if ($titleLower === $q) {
            $score += 100;
        } elseif (str_contains($titleLower, $q)) {
            $score += 50;
        }

        $titleHits = mb_substr_count($titleLower, $q);
        $contentHits = mb_substr_count($contentLower, $q);

        $score += $titleHits * 10;
        $score += min($contentHits, 10) * 2;

        return $score;
    }

    /**
     * Build a ~180-char snippet around the first occurrence of the query.
     */
    protected function makeSnippet(string $text, string $query, int $length = 180): string
    {
        $text = preg_replace('/\s+/', ' ', trim($text));
        if ($text === '') {
            return '';
        }

        $pos = mb_stripos($text, $query);
        if ($pos === false) {
            return Str::limit($text, $length);
        }

        $start = max(0, $pos - 60);
        $snippet = mb_substr($text, $start, $length);

        if ($start > 0) {
            $snippet = '… '.$snippet;
        }
        if (mb_strlen($text) > $start + $length) {
            $snippet .= ' …';
        }

        return $snippet;
    }
}
