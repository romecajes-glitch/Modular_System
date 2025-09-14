<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class QrCode extends Model
{
    protected $fillable = [
        'qr_id',
        'type',
        'data',
        'generated_at',
        'expires_at',
        'is_used',
        'used_at',
        'usage_data',
        'unique_pin'
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'is_used' => 'boolean',
        'data' => 'array'
    ];

    /**
     * Generate multiple QR codes
     */
    public static function generateMultiple($quantity, $type)
    {
        $qrCodes = [];
        
        DB::beginTransaction();
        
        try {
            for ($i = 0; $i < $quantity; $i++) {
                $qrId = Str::uuid()->toString();
                $uniquePin = Str::random(8); // Generate a unique 8-character PIN
                $qrData = [
                    'id' => $qrId,
                    'type' => $type,
                    'generated_at' => now()->toISOString(),
                    'expires_at' => now()->addDays(30)->toISOString(),
                    'unique_pin' => $uniquePin // Include the unique PIN in the QR data
                ];

                $qrCode = self::create([
                    'qr_id' => $qrId,
                    'type' => $type,
                    'data' => json_encode($qrData),
                    'generated_at' => now(),
                    'expires_at' => now()->addDays(30),
                    'is_used' => false,
                    'unique_pin' => $uniquePin // Store the unique PIN
                ]);

                $qrCodes[] = [
                    'id' => $qrId,
                    'type' => $type,
                    'data' => $qrData,
                    'generated_at' => now()->toISOString(),
                    'unique_pin' => $uniquePin, // Include the unique PIN in the response
                    'qr_content' => $uniquePin // Add the QR content (only the unique pin) for encoding
                ];
            }
            
            DB::commit();
            
            return $qrCodes;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Verify if a QR code is valid
     */
    public static function verify($qrData)
    {
        if (!isset($qrData['id']) || !isset($qrData['type'])) {
            return [
                'valid' => false,
                'message' => 'Invalid QR code format'
            ];
        }

        $qrCode = self::where('qr_id', $qrData['id'])
            ->where('type', $qrData['type'])
            ->first();

        if (!$qrCode) {
            return [
                'valid' => false,
                'message' => 'QR code not found'
            ];
        }

        if ($qrCode->is_used) {
            return [
                'valid' => false,
                'message' => 'QR code has already been used'
            ];
        }

        if (now()->gt($qrCode->expires_at)) {
            return [
                'valid' => false,
                'message' => 'QR code has expired'
            ];
        }

        return [
            'valid' => true,
            'message' => 'QR code is valid',
            'qr_code' => $qrCode
        ];
    }

    /**
     * Mark QR code as used
     */
    public function markAsUsed($usageData = null)
    {
        $this->update([
            'is_used' => true,
            'used_at' => now(),
            'usage_data' => $usageData ? json_encode($usageData) : null
        ]);
    }

    /**
     * Get statistics for QR codes
     */
    public static function getStats()
    {
        return self::selectRaw('type, COUNT(*) as total, SUM(CASE WHEN is_used THEN 1 ELSE 0 END) as used_count')
            ->groupBy('type')
            ->get();
    }

    /**
     * Scope for unused QR codes
     */
    public function scopeUnused($query)
    {
        return $query->where('is_used', false)
            ->where('expires_at', '>', now());
    }

    /**
     * Scope for expired QR codes
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * Scope for valid QR codes
     */
    public function scopeValid($query)
    {
        return $query->where('is_used', false)
            ->where('expires_at', '>', now());
    }
}
