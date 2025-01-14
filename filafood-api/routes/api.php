<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\{
    LoginController,
    LogoutController,
    MeController,
    PasswordResetController,
    ProfileController,
    RegisterController,
    SendResetCodeController,
    SubscribeController,
    VerifyResetCodeController,
};

use App\Http\Controllers\Tenant\{
    CategoryController,
    CommandController,
    OrderController,
    ProductController,
    ResourceController,
    RoleController,
    UserController,
};
use App\Http\Middleware\CheckUserPersmissions;
use Laravel\Cashier\Http\Controllers\WebhookController;

//use App\Http\Middleware\Subscribe;

/** 
 * Auth Routes 
 * */
Route::prefix('auth')->group(function () {
    Route::middleware(['guest'])->group(function () {
        Route::post('/register', RegisterController::class)->name('register');
        Route::post('/login', LoginController::class)->name('login');
        Route::post('/password/code', SendResetCodeController::class)->name('sendResetCode');
        Route::post('/password/verify', VerifyResetCodeController::class)->name('verifyResetCode');
        Route::post('/password/reset', PasswordResetController::class)->name('reset');
    });
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/me', MeController::class)->name('me');
        Route::put('/profile', ProfileController::class)->name('profile');
        Route::post('/logout', LogoutController::class)->name('logout');
    });
});

/** 
 * Tenant Routes 
 * */
Route::middleware(['auth:sanctum', CheckUserPersmissions::class])->group(function () {
    Route::apiResource('/commands', CommandController::class)->only(['index', 'store', 'show']);
    Route::apiResource('/orders', OrderController::class)->only(['index', 'show']);
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/products', ProductController::class);
    Route::apiResource('/roles', RoleController::class);
    Route::get('/resources', [ResourceController::class, 'index'])->name('roles.permissions');
    Route::apiResource('/users', UserController::class);
});

/** 
 * Subscribe Routes 
 * */
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/subscribe/bronze', [SubscribeController::class, 'subscribeBronzePlan'])->name('subscribe.bronze');
    Route::post('/subscribe/silver', [SubscribeController::class, 'subscribeSilverPlan'])->name('subscribe.silver');
    Route::post('/subscribe/gold', [SubscribeController::class, 'subscribeGoldPlan'])->name('subscribe.gold');
});

//Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook']);
