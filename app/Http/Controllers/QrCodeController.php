<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    /**
     * Generate QR codes for enrollment or attendance
     */
    public function generateQrCodes(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:100',
            'type' => 'required|in:enrollment,attendance'
        ]);

        $quantity = $request->input('quantity');
        $type = $request->input('type');
        
        $qrCodes = [];
        $qrCodeImages = [];
        
        try {
            DB::beginTransaction();
            
            for ($i = 0; $i < $quantity; $i++) {
                $qrId = Str::uuid()->toString();
                $uniquePin = Str::random(8);
                $qrData = json_encode([
                    'id' => $qrId,
                    'type' => $type,
                    'unique_pin' => $uniquePin,
                    'generated_at' => now()->toISOString(),
                    'expires_at' => now()->addDays(30)->toISOString()
                ]);
                
                // Store QR code in database
                $qrCodeId = DB::table('qr_codes')->insertGetId([
                    'qr_id' => $qrId,
                    'type' => $type,
                    'data' => $qrData,
                    'unique_pin' => $uniquePin,
                    'generated_at' => now(),
                    'expires_at' => now()->addDays(30),
                    'is_used' => false,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                // Generate QR code image
                $qrCodeImage = QrCode::format('svg')->size(300)->generate($qrData);
                $imageData = 'data:image/svg+xml;base64,' . base64_encode($qrCodeImage);
                
                $qrCodes[] = [
                    'id' => $qrId,
                    'type' => $type,
                    'unique_pin' => $uniquePin,
                    'data' => $qrData,
                    'generated_at' => now()->toISOString(),
                    'image' => $imageData
                ];
                
                $qrCodeImages[] = $imageData;
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "$quantity QR codes generated successfully",
                'qr_codes' => $qrCodes,
                'images' => $qrCodeImages
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate QR codes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify a QR code
     */
    public function verifyQrCode(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string'
        ]);

        try {
            $qrData = json_decode($request->input('qr_data'), true);
            
            if (!isset($qrData['id']) || !isset($qrData['type'])) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Invalid QR code format'
                ]);
            }

            $qrCode = DB::table('qr_codes')
                ->where('qr_id', $qrData['id'])
                ->where('type', $qrData['type'])
                ->first();

            if (!$qrCode) {
                return response()->json([
                    'valid' => false,
                    'message' => 'QR code not found'
                ]);
            }

            if ($qrCode->is_used) {
                return response()->json([
                    'valid' => false,
                    'message' => 'QR code has already been used'
                ]);
            }

            if (now()->gt($qrCode->expires_at)) {
                return response()->json([
                    'valid' => false,
                    'message' => 'QR code has expired'
                ]);
            }

            return response()->json([
                'valid' => true,
                'message' => 'QR code is valid',
                'qr_code' => $qrCode
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'valid' => false,
                'message' => 'Error verifying QR code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark QR code as used
     */
    public function markQrCodeUsed(Request $request)
    {
        $request->validate([
            'qr_id' => 'required|string'
        ]);

        try {
            $affected = DB::table('qr_codes')
                ->where('qr_id', $request->input('qr_id'))
                ->update([
                    'is_used' => true,
                    'used_at' => now(),
                    'updated_at' => now()
                ]);

            if ($affected) {
                return response()->json([
                    'success' => true,
                    'message' => 'QR code marked as used'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'QR code not found'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error marking QR code as used: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get QR code statistics
     */
    public function getQrCodeStats()
    {
        try {
            $stats = DB::table('qr_codes')
                ->selectRaw('type, COUNT(*) as total, SUM(CASE WHEN is_used THEN 1 ELSE 0 END) as used_count')
                ->groupBy('type')
                ->get();

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching QR code statistics: ' . $e->getMessage()
            ], 500);
        }
    }
}
