<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

foreach (['scerpv9', 'scerpv8'] as $schema) {
    echo "=== $schema CLASS_TYPE ===\n";
    try {
        $rows = Illuminate\Support\Facades\DB::select(
            "SELECT control_code, meaning, display_sequence FROM {$schema}.sec_control_values
             WHERE control_type = 'CLASS_TYPE' ORDER BY display_sequence"
        );
        print_r($rows);
    } catch (Exception $e) {
        echo $e->getMessage() . "\n";
    }
}
