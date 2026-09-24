<?php

use App\Http\Controllers\Api\V1\AmazonReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::get('amazon-reviews', [AmazonReviewController::class, 'index'])
        ->name('api.v1.amazon-reviews.index');

    Route::get('amazon-reviews/{reviewId}', [AmazonReviewController::class, 'show'])
        ->name('api.v1.amazon-reviews.show');
});
