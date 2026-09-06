<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$rows = Illuminate\Support\Facades\DB::select(
    "SELECT t_code, file_name, flow_path, file_sname
     FROM scerpv9.file_master_erp
     WHERE COALESCE(t_code, '') <> ''
     LIMIT 15"
);
echo "FILES WITH T_CODE:\n";
print_r($rows);

$users = Illuminate\Support\Facades\DB::select('SELECT user_name, user_id, unit_cd FROM scerpv9.user_master');
echo "\nUSERS:\n";
print_r($users);

$school = Illuminate\Support\Facades\DB::selectOne('SELECT code, name, logo, short_name FROM scerpv9.school LIMIT 1');
echo "\nSCHOOL:\n";
print_r($school);

$units = Illuminate\Support\Facades\DB::select('SELECT code, name, comp_code FROM scerpv9.unit LIMIT 5');
echo "\nUNITS:\n";
print_r($units);
