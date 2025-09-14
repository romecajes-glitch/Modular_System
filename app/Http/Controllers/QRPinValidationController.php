<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\QrCode;

class QRPinValidationController extends Controller
{
    /**
     * Validate QR PIN in real-time against database
     */
    public function validateQRPin(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'qr_pin' => 'required|string|size:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'valid' => false,
                'message' => 'PIN must be exactly 8 characters'
            ], 422);
        }

        $pin = $request->input('qr_pin');

        // Check if the PIN exists in the qr_codes table and is not used
        $qrCode = QrCode::where('unique_pin', $pin)
            ->where('is_used', false)
            ->first();

        $isValid = (bool) $qrCode;

        return response()->json([
            'valid' => $isValid,
            'message' => $isValid ? 'Valid PIN - Ready to enroll' : 'Invalid PIN - Please check and try again'
        ]);
    }
}
