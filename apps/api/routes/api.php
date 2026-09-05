<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::get('plans', [PlanController::class, 'index']);
    Route::get('plans/{plan}', [PlanController::class, 'show']);

    Route::prefix('auth')->group(function (): void {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::middleware('auth:sanctum')->group(function (): void {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('me', [AuthController::class, 'me']);
        });
    });
    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('organizations', [OrganizationController::class, 'index']);
        Route::post('organizations', [OrganizationController::class, 'store']);
        Route::get('organizations/{organization}', [OrganizationController::class, 'show']);
        Route::get('organization/context', [OrganizationController::class, 'context'])->middleware('organization.context');
        Route::get('organization/subscription', [SubscriptionController::class, 'current'])->middleware('organization.context');
        Route::post('organization/subscription', [SubscriptionController::class, 'store'])->middleware('organization.context');
    });
});
