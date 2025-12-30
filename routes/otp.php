<?php

/*
|--------------------------------------------------------------------------
| OTP Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\OTPVerificationController;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\SmsTemplateController;
use App\Http\Controllers\Auth\FirebasePhoneController;

//Verofocation phone
Route::controller(OTPVerificationController::class)->group(function () {
    Route::get('/verification', 'verification')->name('verification');
    Route::post('/verification', 'verify_phone')->name('verification.submit');
    Route::get('/verification/phone/code/resend', 'resend_verificcation_code')->name('verification.phone.resend');
    
    //Forgot password phone
    Route::get('/password/phone/reset', 'show_reset_password_form')->name('password.phone.form');
    Route::post('/password/reset/submit', 'reset_password_with_code')->name('password.update.phone');

    // Send OTP
    Route::post('/send-otp', 'sendOtp')->name('send-otp');
    Route::get('/otp-verification', 'otpVerificationPage')->name('otp-verification-page');
    Route::get('/resend-otp/{phone}', 'resendOtp')->name('resend-otp');
    Route::post('/validate-otp-code', 'validateOtpCode')->name('validate-otp-code');
    
});

// Phone login entry + Firebase phone auth (coexists with legacy OTP)
Route::middleware('guest')->group(function () {
    Route::get('/login/phone', [FirebasePhoneController::class, 'entry'])->name('login.phone');
    Route::get('/login/phone/firebase', [FirebasePhoneController::class, 'show'])->name('login.phone.firebase');
    Route::post('/auth/firebase/phone', [FirebasePhoneController::class, 'login'])
        ->middleware('throttle:10,1')
        ->name('auth.firebase.phone');
});

//Admin
Route::group(['prefix' =>'admin', 'middleware' => ['auth', 'admin']], function(){
    Route::controller(OTPController::class)->group(function () {
        Route::get('/otp-login-configuration', 'loginConfigure')->name('otp.login_configuration');
        Route::get('/otp-configuration', 'configure_index')->name('otp.configconfiguration');
        Route::post('/otp-configuration/update/activation', 'updateActivationSettings')->name('otp_configurations.update.activation');
        Route::post('/otp-credentials-update', 'update_credentials')->name('update_credentials');
        Route::post('/otp-provider', 'updateProvider')->name('otp.provider.update');
    });
    //Messaging
    Route::controller(SmsController::class)->group(function () {
        Route::get('/sms', 'index')->name('sms.index');
        Route::post('/sms-send', 'send')->name('sms.send');
    });

    Route::resource('sms-templates', SmsTemplateController::class);
});
