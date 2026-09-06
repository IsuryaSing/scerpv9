<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UserPriv extends Model
{
    protected $table = 'scerpv9.user_priv';

    protected $primaryKey = 'user_priv_id';

    public $timestamps = true;

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $casts = [
        'user_priv_id' => 'integer',
        'from_date' => 'datetime',
        'to_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function modulesForUser(string $userCode): Collection
    {
        return static::query()
            ->select([
                'user_priv.modl',
                DB::raw('MIN(module_ctrl.display_sequence) as display_sequence'),
            ])
            ->join('scerpv9.file_master_erp as files', 'files.file_id', '=', 'user_priv.file_id')
            ->leftJoin('scerpv9.sec_control_values as module_ctrl', function ($join) {
                $join->on('module_ctrl.control_code', '=', 'user_priv.modl')
                    ->where('module_ctrl.control_type', '=', 'MODULE')
                    ->where('module_ctrl.enabled_flag', '=', 'Y');
            })
            ->where('user_priv.user_code', $userCode)
            ->where('user_priv.view_flag', 'Y')
            ->where('files.show_flag', 'Y')
            ->whereNotIn('files.file_type', ['DASHBOARD', 'TERMS', 'MENU'])
            ->groupBy('user_priv.modl')
            ->orderByRaw('MIN(module_ctrl.display_sequence) ASC NULLS LAST')
            ->orderBy('user_priv.modl')
            ->get()
            ->pluck('modl')
            ->filter()
            ->values();
    }

    public static function moduleMeta(): array
    {
        $labels = SecControlValue::moduleLabelMap();

        $defaults = [
            'MAS' => [
                'label' => 'MASTERS',
                'logo' => 'masterslogo.svg',
            ],
            'FIN' => [
                'label' => 'FINANCE',
                'logo' => 'financelogo.svg',
            ],
            'SEC' => [
                'label' => 'SECURITY',
                'logo' => 'securitylogo.svg',
            ],
            'ATT' => [
                'label' => 'ATTENDANCE',
                'logo' => 'attendancelogo.svg',
            ],
            'TCH' => [
                'label' => 'TEACHERS',
                'logo' => 'teacherslogo.svg',
            ],
            'STU' => [
                'label' => 'STUDENTS',
                'logo' => 'studentslogo.svg',
            ],
            'HOD' => [
                'label' => 'HOD',
                'logo' => 'masterslogo.svg',
            ],
        ];

        foreach ($labels as $code => $label) {
            $defaults[$code]['label'] = $label;
            if (! isset($defaults[$code]['logo'])) {
                $defaults[$code]['logo'] = 'masterslogo.svg';
            }
        }

        return $defaults;
    }
}
