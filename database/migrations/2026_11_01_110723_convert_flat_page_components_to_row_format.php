<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Convert flat (non-row) page_components records into the row-based format
     * used by the new Page Builder.
     *
     * Each page that still has non-row component records gets those flat
     * components bundled into a single new 'row' component (1-column, layout '100'),
     * and the original flat records are deleted.
     */
    public function up(): void
    {
        // Fetch all non-row components, ordered so we can reconstruct sort order
        $flatComponents = DB::table('page_components')
            ->where('component_type', '!=', 'row')
            ->orderBy('page_id')
            ->orderBy('sort_order')
            ->get();

        if ($flatComponents->isEmpty()) {
            return;
        }

        // Group by page_id
        $byPage = $flatComponents->groupBy('page_id');

        foreach ($byPage as $pageId => $components) {
            // Build widgets array from flat components
            $widgets = $components->map(function ($c) {
                $content = is_array($c->content)
                    ? $c->content
                    : (json_decode($c->content, true) ?? []);

                return [
                    'id'         => 'w_' . $c->id . '_' . substr(md5(uniqid('', true)), 0, 4),
                    'type'       => $c->component_type,
                    'content'    => $content,
                    'settings'   => [],
                    'is_visible' => (bool) $c->is_visible,
                ];
            })->values()->all();

            // Build the row content
            $rowContent = [
                'columns' => [
                    [
                        'id'      => 'col_migrated_' . $pageId,
                        'widgets' => $widgets,
                    ],
                ],
            ];

            // Use the smallest existing sort_order as the row's sort_order
            $sortOrder = $components->min('sort_order') ?? 1;

            DB::table('page_components')->insert([
                'page_id'        => $pageId,
                'component_type' => 'row',
                'content'        => json_encode($rowContent),
                'settings'       => json_encode(['layout' => '100']),
                'sort_order'     => $sortOrder,
                'is_visible'     => 1,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // Remove the original flat records
            $ids = $components->pluck('id')->all();
            DB::table('page_components')->whereIn('id', $ids)->delete();
        }
    }

    /**
     * Reverse the migration: unwrap each row's widgets back to flat components.
     * This is best-effort — original sort_orders are approximated.
     */
    public function down(): void
    {
        $rowComponents = DB::table('page_components')
            ->where('component_type', 'row')
            ->get();

        foreach ($rowComponents as $row) {
            $content = is_array($row->content)
                ? $row->content
                : (json_decode($row->content, true) ?? []);

            $columns = $content['columns'] ?? [];
            $sortOrder = $row->sort_order;

            foreach ($columns as $col) {
                $widgets = $col['widgets'] ?? [];
                foreach ($widgets as $widget) {
                    DB::table('page_components')->insert([
                        'page_id'        => $row->page_id,
                        'component_type' => $widget['type'] ?? 'html',
                        'content'        => json_encode($widget['content'] ?? []),
                        'settings'       => json_encode($widget['settings'] ?? []),
                        'sort_order'     => $sortOrder++,
                        'is_visible'     => isset($widget['is_visible']) ? (int) $widget['is_visible'] : 1,
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]);
                }
            }

            DB::table('page_components')->where('id', $row->id)->delete();
        }
    }
};
