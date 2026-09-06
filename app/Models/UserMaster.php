<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class UserMaster extends Authenticatable
{
    protected $table = 'scerpv9.user_master';

    protected $primaryKey = 'user_master_id';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = true;

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'user_id',
        'user_name',
        'user_fname',
        'user_pass',
        'valid_from',
        'valid_to',
        'validity',
        'emp_id',
        'no_off_session',
        'emp_name',
        'last_pwd_changed',
        'emp_cd',
        'unit_cd',
        'pwd_chang_days',
        'pwd_msg_bfr_day',
        'last_updated_by',
        'created_by',
        'object_version_number',
        'first_login',
        'role_centre',
        'ip_add',
        'ip_user',
        'confirm_pwd',
        'bdc_flag',
        'company_code',
        'creation_date',
        'token',
        'report_password',
    ];

    protected $hidden = [
        'user_pass',
        'confirm_pwd',
        'report_password',
        'token',
    ];

    protected $casts = [
        'user_master_id' => 'integer',
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
        'last_pwd_changed' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'creation_date' => 'date',
        'no_off_session' => 'float',
        'pwd_chang_days' => 'float',
        'pwd_msg_bfr_day' => 'float',
        'object_version_number' => 'integer',
    ];

    public function getAuthPassword()
    {
        return $this->user_pass;
    }

    public function getAuthIdentifierName()
    {
        return 'user_master_id';
    }

    public static function findByUsername(string $username): ?self
    {
        return static::query()
            ->where('user_name', $username)
            ->first();
    }

    public function validatePassword(string $password): bool
    {
        $storedPassword = (string) $this->user_pass;

        if ($storedPassword === $password) {
            return true;
        }

        if ($this->isBcryptHash($storedPassword)) {
            return password_verify($password, $storedPassword) || Hash::check($password, $storedPassword);
        }

        return false;
    }

    public function isActive(): bool
    {
        return strtoupper(trim((string) $this->validity)) === 'Y';
    }

    public function isWithinValidityPeriod(): bool
    {
        if ($this->valid_from && now()->startOfDay()->lt($this->valid_from->copy()->startOfDay())) {
            return false;
        }

        if ($this->valid_to && now()->startOfDay()->gt($this->valid_to->copy()->startOfDay())) {
            return false;
        }

        return true;
    }

    public function matchesUnit(string $unitCode): bool
    {
        return (string) $this->unit_cd === (string) $unitCode;
    }

    /**
     * @return array{valid: bool, message: string, field: string}
     */
    public function validateLogin(string $password, string $unitCode): array
    {
        if (! $this->validatePassword($password)) {
            return [
                'valid' => false,
                'message' => 'Invalid username or password.',
                'field' => 'username',
            ];
        }

        if (! $this->isActive()) {
            return [
                'valid' => false,
                'message' => 'Your account is inactive. Please contact the administrator.',
                'field' => 'username',
            ];
        }

        if ($this->valid_from && now()->startOfDay()->lt($this->valid_from->copy()->startOfDay())) {
            return [
                'valid' => false,
                'message' => 'Your account is not active yet.',
                'field' => 'username',
            ];
        }

        if ($this->valid_to && now()->startOfDay()->gt($this->valid_to->copy()->startOfDay())) {
            return [
                'valid' => false,
                'message' => 'Your account validity has expired.',
                'field' => 'username',
            ];
        }

        if (! $this->matchesUnit($unitCode)) {
            return [
                'valid' => false,
                'message' => 'Selected unit does not match your account.',
                'field' => 'unit',
            ];
        }

        return [
            'valid' => true,
            'message' => '',
            'field' => '',
        ];
    }

    protected function isBcryptHash(string $value): bool
    {
        return preg_match('/^\$2[aby]\$\d{2}\$/', $value) === 1;
    }
}
