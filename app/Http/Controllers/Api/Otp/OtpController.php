<?php

namespace App\Http\Controllers\Api\Otp;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use IchTrojan\Otp\Otp;
use Illuminate\Support\Facades\Mail;
class OtpController extends Controller
{
public function sendOtp(Request $request)
{
    $request->validate(['email' => 'required|email']);


    $otp = rand(100000, 999999);

    \Illuminate\Support\Facades\Cache::put('otp_' . $request->email, $otp, now()->addMinutes(10));

    try {

        \Illuminate\Support\Facades\Mail::raw("Your OTP code is: {$otp}", function ($message) use ($request) {
            $message->to($request->email)->subject('OTP Verification');
        });

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully!',
            'otp'     => $otp
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

public function verifyOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'otp'   => 'required'
    ]);


    $storedOtp = \Illuminate\Support\Facades\Cache::get('otp_' . $request->email);


    if ($storedOtp && $storedOtp == $request->otp) {

        \Illuminate\Support\Facades\Cache::forget('otp_' . $request->email);

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully!'
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Invalid or expired OTP.'
    ], 422);
}
}
