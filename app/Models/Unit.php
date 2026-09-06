<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'scerpv9.unit';

    protected $primaryKey = 'code';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $casts = [
        'code' => 'integer',
        'operation_from' => 'date',
        'creation_date' => 'datetime',
        'last_updated_date' => 'datetime',
        'comp_code' => 'integer',
    ];
}
