<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

foreach (['file_master_erp', 'school'] as $table) {
    echo "=== $table ===\n";
    $cols = Illuminate\Support\Facades\DB::select(
        "SELECT column_name, data_type FROM information_schema.columns
         WHERE table_schema = 'scerpv9' AND table_name = ?
         ORDER BY ordinal_position",
        [$table]
    );
    foreach ($cols as $c) {
        echo $c->column_name . ' (' . $c->data_type . ")\n";
    }
    $rows = Illuminate\Support\Facades\DB::select("SELECT * FROM scerpv9.$table LIMIT 3");
    print_r($rows);
    echo "\n";
}
