<?php

use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\V1\UserRoleController;
use Illuminate\Support\Facades\Route;

Route::post('login', [UserController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('user-roles-list', [UserRoleController::class, 'userRoleList']);
});