<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Check if program ID 1 exists
$program = DB::table('programs')->where('id', 1)->first();

if ($program) {
    echo "Program ID 1 found:\n";
    echo "Name: " . $program->name . "\n";
    echo "Description: " . ($program->description ?? 'No description') . "\n";
    echo "Status: " . ($program->status ?? 'Active') . "\n";
} else {
    echo "Program ID 1 not found in the database.\n";
    
    // List all available programs
    $allPrograms = DB::table('programs')->get();
    echo "\nAvailable programs:\n";
    foreach ($allPrograms as $prog) {
        echo "ID: " . $prog->id . " - Name: " . $prog->name . "\n";
    }
}
?>
