<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$functions = ['generate_student_code', 'generate_teacher_code', 'manage_form_table'];

foreach ($functions as $name) {
    $rows = Illuminate\Support\Facades\DB::select(
        'SELECT pg_get_functiondef(p.oid) AS definition
         FROM pg_proc p
         JOIN pg_namespace n ON n.oid = p.pronamespace
         WHERE n.nspname = ? AND p.proname = ?',
        ['scportal', $name]
    );

    echo "===== {$name} =====\n";
    foreach ($rows as $row) {
        echo $row->definition . "\n\n";
    }
}
