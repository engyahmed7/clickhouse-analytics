<?php

use App\Http\Controllers\Api\V1\AmazonReviewController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('amazon-reviews', [AmazonReviewController::class, 'index'])
        ->name('api.v1.amazon-reviews.index');
});
