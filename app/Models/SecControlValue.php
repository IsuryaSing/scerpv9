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
}
