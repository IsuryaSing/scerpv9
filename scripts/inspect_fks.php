<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$fks = Illuminate\Support\Facades\DB::select(
    "SELECT tc.table_name, tc.constraint_name, kcu.column_name,
            ccu.table_schema AS foreign_table_schema,
            ccu.table_name AS foreign_table_name,
            ccu.column_name AS foreign_column_name
     FROM information_schema.table_constraints tc
     JOIN information_schema.key_column_usage kcu
       ON tc.constraint_name = kcu.constraint_name
      AND tc.table_schema = kcu.table_schema
     JOIN information_schema.constraint_column_usage ccu
       ON ccu.constraint_name = tc.constraint_name
      AND ccu.table_schema = tc.table_schema
     WHERE tc.constraint_type = 'FOREIGN KEY'
       AND tc.table_schema = 'scportal'
     ORDER BY tc.table_name"
);

foreach ($fks as $fk) {
    echo "{$fk->table_name}.{$fk->column_name} -> {$fk->foreign_table_schema}.{$fk->foreign_table_name}.{$fk->foreign_column_name}\n";
}
