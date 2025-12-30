<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Firebase\Exception\InvalidArgumentException;

class FirebasePhoneController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function entry()
    {
        if (otp_provider() === 'firebase') {
            return redirect()->route('login.phone.firebase');
        }

        return redirect()->route('user.login');
    }

    public function show()
    {
        $layout = get_setting('authentication_layout_select') ?? 'boxed';
        $view = 'auth.' . $layout . '.firebase_phone_login';

        if (!view()->exists($view)) {
            $view = 'auth.boxed.firebase_phone_login';
        }

        return view($view);
    }

    public function login(Request $request, FirebaseAuth $firebaseAuth): JsonResponse
    {
        $request->validate([
            'idToken' => 'required|string',
        ]);

        try {
            $verifiedIdToken = $firebaseAuth->verifyIdToken($request->idToken);
            $firebaseUid = $verifiedIdToken->claims()->get('sub');
            $phoneNumber = $verifiedIdToken->claims()->get('phone_number');
        } catch (FailedToVerifyToken|InvalidArgumentException $e) {
            Log::warning('Firebase phone login failed', ['message' => $e->getMessage()]);
            return response()->json(['message' => translate('Invalid or expired Firebase token.')], 422);
        }

        if (!$firebaseUid || !$phoneNumber) {
            return response()->json(['message' => translate('Unable to read phone number from Firebase token.')], 422);
        }

        $user = User::where('firebase_uid', $firebaseUid)
            ->orWhere('phone', $phoneNumber)
            ->first();

        if (!$user) {
            $user = new User();
            $user->name = 'Firebase User ' . Str::random(6);
            $user->email = null;
            $user->phone = $phoneNumber;
            $user->password = Hash::make(Str::random(32));
            $user->email_verified_at = now();
            $user->verification_code = null;
            $user->user_type = 'customer';
        }

        if (!$user->firebase_uid) {
            $user->firebase_uid = $firebaseUid;
        }

        if (!$user->phone) {
            $user->phone = $phoneNumber;
        }

        $user->save();

        if ($user->user_type === 'customer' && !$user->customer) {
            Customer::firstOrCreate(['user_id' => $user->id]);
        }

        Auth::login($user, true);

        $redirect = session('link') ?? route('dashboard');

        return response()->json(['redirect' => $redirect]);
    }
}
