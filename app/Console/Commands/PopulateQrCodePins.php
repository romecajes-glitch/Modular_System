<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\QrCode;
use Illuminate\Support\Str;

class PopulateQrCodePins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:populate-qr-code-pins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate unique_pin for existing QR codes that don\'t have one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to populate unique_pin for existing QR codes...');

        // Get all QR codes that don't have a unique_pin
        $qrCodesWithoutPin = QrCode::whereNull('unique_pin')->get();

        if ($qrCodesWithoutPin->isEmpty()) {
            $this->info('All QR codes already have unique_pin populated.');
            return;
        }

        $this->info("Found {$qrCodesWithoutPin->count()} QR codes without unique_pin.");

        $bar = $this->output->createProgressBar($qrCodesWithoutPin->count());
        $bar->start();

        $updated = 0;
        foreach ($qrCodesWithoutPin as $qrCode) {
            // Generate a unique 8-character PIN
            $uniquePin = Str::random(8);

            // Make sure it's unique in the database
            while (QrCode::where('unique_pin', $uniquePin)->exists()) {
                $uniquePin = Str::random(8);
            }

            $qrCode->update(['unique_pin' => $uniquePin]);
            $updated++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $this->info("Successfully populated unique_pin for {$updated} QR codes.");
    }
}
