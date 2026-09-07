<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'scerpv9.subject';

    protected $primaryKey = 'subject_id';

    public $timestamps = true;

    protected $fillable = [
        'class',
        'subject_code',
        'subject_name',
        'sl_no',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'subject_id' => 'integer',
        'sl_no' => 'integer',
    ];
}
