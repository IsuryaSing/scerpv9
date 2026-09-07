<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'scerpv9.students';

    protected $primaryKey = 'student_id';

    public $timestamps = true;

    protected $fillable = [
        'admission_no',
        'student_code',
        'roll_no',
        'full_name',
        'date_of_birth',
        'gender',
        'blood_group',
        'nationality',
        'religion',
        'category',
        'caste',
        'mother_tongue',
        'aadhar_no',
        'birth_certificate_no',
        'place_of_birth',
        'admission_date',
        'admission_class',
        'student_photo',
        'status',
        'leaving_date',
        'leaving_reason',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'student_id' => 'integer',
        'date_of_birth' => 'date',
        'admission_date' => 'date',
        'leaving_date' => 'date',
    ];
}
