<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class FeedController extends Controller
{
    /**
     * Serve RSS 2.0 feed of latest published blog posts.
     */
    public function rss(): Response
    {
        $siteName = Setting::get('site_name', 'general', 'MonetraPay');
        $description = Setting::get('meta_description_default', 'seo')
            ?? Setting::get('tagline', 'general')
            ?? 'Latest articles from '.$siteName;

        $blogs = Blog::where('status', 'published')
            ->with(['author', 'categories'])
            ->orderByDesc('published_at')
            ->limit(50)
            ->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:dc="http://purl.org/dc/elements/1.1/">'."\n";
        $xml .= '<channel>'."\n";
        $xml .= '    <title>'.$this->xmlEscape($siteName.' - Blog').'</title>'."\n";
        $xml .= '    <link>'.url('/blog').'</link>'."\n";
        $xml .= '    <description>'.$this->xmlEscape($description).'</description>'."\n";
        $xml .= '    <language>id-ID</language>'."\n";
        $xml .= '    <atom:link href="'.url('/feed.xml').'" rel="self" type="application/rss+xml" />'."\n";
        $xml .= '    <lastBuildDate>'.now()->toRfc2822String().'</lastBuildDate>'."\n";

        foreach ($blogs as $blog) {
            $url = route('blog.show', $blog->slug);
            $xml .= "    <item>\n";
            $xml .= '        <title>'.$this->xmlEscape($blog->title).'</title>'."\n";
            $xml .= '        <link>'.$url.'</link>'."\n";
            $xml .= '        <guid isPermaLink="true">'.$url.'</guid>'."\n";

            $author = $blog->author;
            if ($author?->name) {
                $xml .= '        <dc:creator>'.$this->xmlEscape($author->name).'</dc:creator>'."\n";
            }

            $pubDate = $blog->published_at ?? $blog->created_at;
            $xml .= '        <pubDate>'.$pubDate->toRfc2822String().'</pubDate>'."\n";

            foreach ($blog->categories as $cat) {
                /** @var Category $cat */
                $xml .= '        <category>'.$this->xmlEscape($cat->name).'</category>'."\n";
            }

            $excerpt = $blog->excerpt ?: Str::limit(strip_tags($blog->content), 280);
            $xml .= '        <description>'.$this->xmlEscape($excerpt).'</description>'."\n";
            $xml .= '        <content:encoded><![CDATA['.$blog->content.']]></content:encoded>'."\n";

            $xml .= "    </item>\n";
        }

        $xml .= '</channel>'."\n";
        $xml .= '</rss>'."\n";

        return response($xml, 200, [
            'Content-Type' => 'application/rss+xml; charset=utf-8',
        ]);
    }

    protected function xmlEscape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
