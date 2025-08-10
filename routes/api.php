<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

// مسارات المصادقة
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// المسارات المحمية
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // مسارات تحتاج إلى دور المدير
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/users', function () {
            return response()->json(['message' => 'قائمة المستخدمين - مدير فقط']);
        });
    });

    // مسارات تحتاج إلى صلاحية معينة
    Route::middleware('permission:view_users')->group(function () {
        Route::get('/users', function () {
            return response()->json(['message' => 'قائمة المستخدمين']);
        });
    });
});
