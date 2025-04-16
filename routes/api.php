<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\GetCurrentUserController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\LogoutController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::apiResources( [
        'users' => UserController::class,
        'roles' => RoleController::class,
        'articles' => ArticleController::class,
    ]);

    // current user
    Route::get('current-user', GetCurrentUserController::class);

    // logout
    Route::delete('logout', LogoutController::class);
});

// login
Route::post('admin/login', LoginController::class);
