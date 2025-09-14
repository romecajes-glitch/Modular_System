<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Program;

echo "Programs count: " . Program::count() . PHP_EOL;
echo "Sample programs:" . PHP_EOL;

$programs = Program::take(3)->get();
foreach ($programs as $program) {
    echo $program->id . ': ' . $program->name . PHP_EOL;
}
