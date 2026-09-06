<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$cols = Illuminate\Support\Facades\DB::select(
    "SELECT column_name, data_type FROM information_schema.columns
     WHERE table_schema = 'scerpv9' AND table_name = 'user_priv'
     ORDER BY ordinal_position"
);

echo "COLUMNS:\n";
foreach ($cols as $c) {
    echo $c->column_name . ' (' . $c->data_type . ")\n";
}

$rows = Illuminate\Support\Facades\DB::select('SELECT * FROM scerpv9.user_priv LIMIT 10');
echo "\nSAMPLE ROWS:\n";
print_r($rows);

$modules = Illuminate\Support\Facades\DB::select(
    'SELECT DISTINCT modl FROM scerpv9.user_priv ORDER BY modl'
);
echo "\nDISTINCT MODL:\n";
foreach ($modules as $m) {
    echo $m->modl . PHP_EOL;
}
