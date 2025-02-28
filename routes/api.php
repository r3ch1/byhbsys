<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

use App\Http\Controllers\{RegisterController, TransactionController};
use App\Actions\SendWhatsappMessageAction;



Route::post('sanctum/token', function (Request $request) {
    try {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $user->createToken($request->device_name)->plainTextToken;
    } catch (ValidationException $e) {
        dd($e);
    }
});
Route::middleware('auth:sanctum')->group( function () {
    Route::controller(RegisterController::class)->group(function(){
        Route::post('register', 'register');
        Route::post('login', 'login')->withoutMiddleware('auth:sanctum');
    });

    Route::resource('users', UserController::class)->only(['index']);
    Route::resource('transactions', TransactionController::class)->except(['create', 'edit']);

    Route::prefix('whatsapp')->withoutMiddleware('auth:sanctum')->group(function(){
        Route::post('webhook', function(){
            Log::info('WEBHOOK WP', request()->all());
            return '<?xml version="1.0" encoding="UTF-8"?>
            <Response>
                <Message>
                    Olá Mundo!
                </Message>
            </Response>';
        });
        Route::get('', [TransactionController::class, 'action']);
        // Route::get('', function() {
        //     dd('HEROC');
        // });
        // Route::get('whatsapp', [WhatsAppController::class, 'index']);
        // Route::post('whatsapp', [WhatsAppController::class, 'store'])->name('whatsapp.post');
    });

});

// Route::get('user', function (Request $request) {
//     dd('dsa');
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::get('test', function() {
//     dd('qqq');
// })->middleware('auth:sanctum');

