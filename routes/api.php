<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::apiResources( [
        'users' => UserController::class,
        'articles' => ArticleController::class,
    ]);
});
