<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tables = Illuminate\Support\Facades\DB::select(
    "SELECT table_name FROM information_schema.tables
     WHERE table_schema = 'scerpv9' AND table_name LIKE '%student%'
     ORDER BY table_name"
);

echo "TABLES:\n";
foreach ($tables as $row) {
    echo $row->table_name . "\n";
}

$main = 'students';
$cols = Illuminate\Support\Facades\DB::select(
    "SELECT column_name, data_type, character_maximum_length
     FROM information_schema.columns
     WHERE table_schema = 'scerpv9' AND table_name = ?
     ORDER BY ordinal_position",
    [$main]
);

echo "\nCOLUMNS for {$main}:\n";
foreach ($cols as $col) {
    echo $col->column_name . ' (' . $col->data_type . ")\n";
}

$count = Illuminate\Support\Facades\DB::table('scerpv9.' . $main)->count();
echo "\nRow count: {$count}\n";

if ($count > 0) {
    $sample = Illuminate\Support\Facades\DB::table('scerpv9.' . $main)->first();
    echo "Sample: " . json_encode($sample) . "\n";
}
