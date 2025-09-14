<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Check the QR code with PIN "fbNl7eLt"
$qrCode = DB::table('qr_codes')
    ->where('unique_pin', 'fbNl7eLt')
    ->first();

if ($qrCode) {
    echo "QR Code found:\n";
    echo "ID: " . $qrCode->id . "\n";
    echo "QR ID: " . $qrCode->qr_id . "\n";
    echo "Type: " . $qrCode->type . "\n";
    echo "Unique PIN: " . $qrCode->unique_pin . "\n";
    echo "Is Used: " . ($qrCode->is_used ? 'Yes' : 'No') . "\n";
    echo "Used At: " . ($qrCode->used_at ? $qrCode->used_at : 'Not used yet') . "\n";
    echo "Generated At: " . $qrCode->generated_at . "\n";
    echo "Expires At: " . $qrCode->expires_at . "\n";
} else {
    echo "QR Code with PIN 'fbNl7eLt' not found in the database.\n";
}
?>
