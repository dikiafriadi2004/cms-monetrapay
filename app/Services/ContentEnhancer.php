<?php

namespace App\Services;

class ContentEnhancer
{
    /**
     * Enhance content HTML for frontend rendering:
     * - Add loading="lazy" + decoding="async" to <img> tags that lack them.
     * - Add rel="noopener noreferrer" to external <a target="_blank">.
     */
    public function enhance(?string $html): string
    {
        if (empty($html)) {
            return '';
        }

        // Lazy-load images that don't already have loading attribute
        $html = preg_replace_callback(
            '/<img\b([^>]*)>/i',
            function ($match) {
                $attrs = $match[1];

                if (! preg_match('/\bloading\s*=/i', $attrs)) {
                    $attrs .= ' loading="lazy"';
                }
                if (! preg_match('/\bdecoding\s*=/i', $attrs)) {
                    $attrs .= ' decoding="async"';
                }

                return '<img'.$attrs.'>';
            },
            $html
        );

        // Add rel="noopener noreferrer" to target="_blank" anchors
        $html = preg_replace_callback(
            '/<a\b([^>]*\btarget\s*=\s*["\']_blank["\'][^>]*)>/i',
            function ($match) {
                $attrs = $match[1];

                if (! preg_match('/\brel\s*=/i', $attrs)) {
                    $attrs .= ' rel="noopener noreferrer"';
                }

                return '<a'.$attrs.'>';
            },
            $html
        );

        return $html;
    }
}
