<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class FileMasterErp extends Model
{
    protected $table = 'scerpv9.file_master_erp';

    protected $primaryKey = 'id';

    public $timestamps = false;

    public static function searchForUser(string $userCode, string $query, int $limit = 12): Collection
    {
        $term = trim($query);

        if ($term === '') {
            return collect();
        }

        $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $term) . '%';

        return static::query()
            ->select([
                'file_master_erp.t_code',
                'file_master_erp.file_name',
                'file_master_erp.file_sname',
                'file_master_erp.flow_path',
            ])
            ->join('scerpv9.user_priv as up', 'up.file_id', '=', 'file_master_erp.file_id')
            ->where('up.user_code', $userCode)
            ->where('up.view_flag', 'Y')
            ->where('file_master_erp.show_flag', 'Y')
            ->whereNotNull('file_master_erp.t_code')
            ->where('file_master_erp.t_code', '<>', '')
            ->where(function ($builder) use ($like) {
                $builder
                    ->where('file_master_erp.t_code', 'ILIKE', $like)
                    ->orWhere('file_master_erp.file_name', 'ILIKE', $like)
                    ->orWhere('file_master_erp.file_sname', 'ILIKE', $like);
            })
            ->orderBy('file_master_erp.t_code')
            ->limit($limit)
            ->get()
            ->unique('t_code')
            ->values();
    }
}
