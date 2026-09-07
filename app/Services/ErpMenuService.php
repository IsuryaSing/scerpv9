<?php

namespace App\Services;

use App\Models\FileMasterErp;
use App\Models\UserPriv;
use Illuminate\Support\Collection;

class ErpMenuService
{
    public function buildSidebarMenu(string $userCode, string $module): array
    {
        $moduleCode = strtoupper($module);

        $privilegedFiles = FileMasterErp::query()
            ->select([
                'file_master_erp.file_id',
                'file_master_erp.file_sname',
                'file_master_erp.file_type',
                'file_master_erp.file_menu_id',
                'file_master_erp.flow_path',
                'file_master_erp.display_order',
                'file_master_erp.ico_file',
            ])
            ->join('scerpv9.user_priv as up', 'up.file_id', '=', 'file_master_erp.file_id')
            ->where('up.user_code', $userCode)
            ->where('up.view_flag', 'Y')
            ->where('file_master_erp.modl', $moduleCode)
            ->where('file_master_erp.show_flag', 'Y')
            ->whereNotIn('file_master_erp.file_type', ['TERMS', 'DASHBOARD'])
            ->orderBy('file_master_erp.display_order')
            ->orderBy('file_master_erp.file_id')
            ->get()
            ->keyBy('file_id');

        if ($privilegedFiles->isEmpty()) {
            return [];
        }

        $moduleFiles = FileMasterErp::query()
            ->select([
                'file_id',
                'file_sname',
                'file_type',
                'file_menu_id',
                'flow_path',
                'display_order',
                'ico_file',
            ])
            ->where('modl', $moduleCode)
            ->where('show_flag', 'Y')
            ->whereNotIn('file_type', ['TERMS', 'DASHBOARD'])
            ->get()
            ->keyBy('file_id');

        $visibleIds = $this->collectVisibleFileIds($privilegedFiles, $moduleFiles);
        $visibleFiles = $moduleFiles
            ->filter(fn ($file, $fileId) => $visibleIds->contains($fileId))
            ->values();

        return $this->buildTree($visibleFiles, 'TERMS0001');
    }

    public function moduleLabel(string $module): string
    {
        $meta = UserPriv::moduleMeta();

        return $meta[strtoupper($module)]['label'] ?? strtoupper($module);
    }

    protected function collectVisibleFileIds(Collection $privilegedFiles, Collection $moduleFiles): Collection
    {
        $visible = collect($privilegedFiles->keys());

        foreach ($privilegedFiles as $file) {
            $parentId = $file->file_menu_id;

            while ($parentId && $moduleFiles->has($parentId)) {
                if (! $visible->contains($parentId)) {
                    $visible->push($parentId);
                }

                $parentId = $moduleFiles->get($parentId)->file_menu_id;
            }
        }

        return $visible->unique()->values();
    }

    protected function buildTree(Collection $files, string $rootParentId): array
    {
        $byParent = $files->groupBy(function ($file) {
            return $file->file_menu_id ?: '';
        });

        $build = function (string $parentId) use (&$build, $byParent): array {
            $items = $byParent->get($parentId, collect())
                ->sortBy([
                    ['display_order', 'asc'],
                    ['file_id', 'asc'],
                ]);

            return $items->map(function ($file) use (&$build) {
                $children = $build($file->file_id);

                return [
                    'file_id' => $file->file_id,
                    'label' => $file->file_sname,
                    'url' => $this->resolveUrl($file->flow_path),
                    'icon' => $file->ico_file ?: 'fa-angle-right',
                    'type' => $file->file_type,
                    'children' => $children,
                    'has_children' => count($children) > 0,
                ];
            })->values()->all();
        };

        return $build($rootParentId);
    }

    protected function resolveUrl(?string $flowPath): string
    {
        if (! $flowPath || $flowPath === '#') {
            return '#';
        }

        if (str_starts_with($flowPath, 'http://') || str_starts_with($flowPath, 'https://')) {
            return $flowPath;
        }

        return url($flowPath);
    }
}
