<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class SecControlValue extends Model
{
    protected $table = 'scerpv9.sec_control_values';

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'display_sequence' => 'integer',
    ];

    public static function moduleOrder(): Collection
    {
        return static::query()
            ->where('control_type', 'MODULE')
            ->where('enabled_flag', 'Y')
            ->orderBy('display_sequence')
            ->get(['control_code', 'meaning', 'display_sequence']);
    }

    public static function moduleLabelMap(): array
    {
        return static::moduleOrder()
            ->mapWithKeys(fn (self $row) => [
                $row->control_code => strtoupper(trim((string) $row->meaning)),
            ])
            ->all();
    }

    public static function classTypes(): array
    {
        $rows = static::query()
            ->where('control_type', 'CLASS_TYPE')
            ->where('enabled_flag', 'Y')
            ->orderBy('display_sequence')
            ->get(['control_code', 'meaning']);

        if ($rows->isEmpty()) {
            static::seedClassTypes();
            $rows = static::query()
                ->where('control_type', 'CLASS_TYPE')
                ->where('enabled_flag', 'Y')
                ->orderBy('display_sequence')
                ->get(['control_code', 'meaning']);
        }

        return $rows
            ->map(fn (self $row) => [
                'code' => $row->control_code,
                'name' => $row->meaning,
            ])
            ->all();
    }

    protected static function seedClassTypes(): void
    {
        $classes = [
            ['NUR', 'Nursery', 1],
            ['LKG', 'LKG', 2],
            ['UKG', 'UKG', 3],
            ['1', 'Class 1', 4],
            ['2', 'Class 2', 5],
            ['3', 'Class 3', 6],
            ['4', 'Class 4', 7],
            ['5', 'Class 5', 8],
            ['6', 'Class 6', 9],
            ['7', 'Class 7', 10],
            ['8', 'Class 8', 11],
            ['9', 'Class 9', 12],
            ['10', 'Class 10', 13],
            ['11', 'Class 11', 14],
            ['12', 'Class 12', 15],
        ];

        foreach ($classes as [$code, $meaning, $sequence]) {
            static::query()->insert([
                'control_type' => 'CLASS_TYPE',
                'control_code' => $code,
                'meaning' => $meaning,
                'enabled_flag' => 'Y',
                'display_sequence' => $sequence,
            ]);
        }
    }
}
