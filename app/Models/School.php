<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $table = 'scerpv9.school';

    protected $primaryKey = 'code';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;
}
