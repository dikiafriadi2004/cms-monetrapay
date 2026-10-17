<?php

namespace App\Services;

use Illuminate\Support\Collection;

class AdInsertionService
{
    /**
     * Calculate positions where ads should be inserted.
     * For interval N, positions are: N, 2N, 3N, ... up to paragraph count P.
     * If P < N, return empty array (no ads inserted).
     *
     * @param  int  $paragraphCount  Total number of paragraphs in content
     * @param  int  $interval  Insert ad every N paragraphs (2-10)
     * @return array Array of paragraph positions (1-indexed) after which to insert ads
     */
    public function calculateInsertPositions(int $paragraphCount, int $interval): array
    {
        if ($paragraphCount < $interval || $interval <= 0) {
            return [];
        }

        $positions = [];
        for ($i = $interval; $i <= $paragraphCount; $i += $interval) {
            $positions[] = $i;
        }

        return $positions;
    }

    /**
     * Insert ads into blog content at calculated positions.
     * Does NOT modify stored content — only transforms for rendering.
     * Splits content by <p>...</p> tags, inserts ad HTML after specified positions.
     * Original paragraph content and order remain unchanged.
     *
     * @param  string  $htmlContent  The blog post HTML content
     * @param  Collection  $activeAds  Active auto-insert ad slots with 'code' or 'image' and 'interval'
     * @return string HTML content with ads inserted
     */
    public function insertAdsInContent(string $htmlContent, Collection $activeAds): string
    {
        if ($activeAds->isEmpty() || empty($htmlContent)) {
            return $htmlContent;
        }

        // Split content into paragraphs while preserving the full HTML
        // Match all <p>...</p> tags (including attributes and nested content)
        preg_match_all('/<p[^>]*>.*?<\/p>/is', $htmlContent, $matches, PREG_OFFSET_CAPTURE);

        if (empty($matches[0])) {
            return $htmlContent;
        }

        $paragraphs = $matches[0]; // Each element is [matched_string, offset]
        $paragraphCount = count($paragraphs);

        // Collect all ad insertions with their positions
        $insertions = []; // position => [ad_html, ad_html, ...]

        foreach ($activeAds as $ad) {
            $interval = $ad->interval ?? null;

            if ($interval === null || $interval <= 0) {
                continue;
            }

            $positions = $this->calculateInsertPositions($paragraphCount, $interval);
            $adHtml = $this->buildAdHtml($ad);

            foreach ($positions as $position) {
                if (! isset($insertions[$position])) {
                    $insertions[$position] = [];
                }
                $insertions[$position][] = $adHtml;
            }
        }

        if (empty($insertions)) {
            return $htmlContent;
        }

        // Sort positions in reverse order so we can insert from the end
        // without affecting earlier offsets
        krsort($insertions);

        $result = $htmlContent;

        foreach ($insertions as $position => $adHtmlArray) {
            // Position is 1-indexed, so paragraph at index (position - 1)
            $paragraphIndex = $position - 1;

            if ($paragraphIndex >= count($paragraphs)) {
                continue;
            }

            $paragraph = $paragraphs[$paragraphIndex];
            $paragraphText = $paragraph[0];
            $paragraphOffset = $paragraph[1];

            // Insert after this paragraph
            $insertPoint = $paragraphOffset + strlen($paragraphText);
            $adsToInsert = implode('', $adHtmlArray);

            $result = substr($result, 0, $insertPoint).$adsToInsert.substr($result, $insertPoint);
        }

        return $result;
    }

    /**
     * Build the HTML wrapper for an ad slot.
     *
     * @param  mixed  $ad  The ad model instance
     * @return string HTML for the ad
     */
    protected function buildAdHtml($ad): string
    {
        $content = '';

        if (! empty($ad->code)) {
            $content = $ad->code;
        } elseif (! empty($ad->image)) {
            $content = '<img src="'.e($ad->image).'" alt="'.e($ad->name ?? 'Advertisement').'">';
        }

        return '<div class="ad-slot ad-auto-insert">'.$content.'</div>';
    }
}
