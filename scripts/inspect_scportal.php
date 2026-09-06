<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tables = Illuminate\Support\Facades\DB::select(
    "SELECT table_name FROM information_schema.tables
     WHERE table_schema = 'scportal' AND table_type = 'BASE TABLE'
     ORDER BY table_name"
);

echo "TABLES:\n";
foreach ($tables as $t) {
    echo $t->table_name . "\n";
}

$functions = Illuminate\Support\Facades\DB::select(
    "SELECT routine_name FROM information_schema.routines
     WHERE routine_schema = 'scportal' AND routine_type = 'FUNCTION'
     ORDER BY routine_name"
);

echo "\nFUNCTIONS:\n";
foreach ($functions as $f) {
    echo $f->routine_name . "\n";
}
