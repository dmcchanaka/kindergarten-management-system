<?php

use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\V1\UserRoleController;
use Illuminate\Support\Facades\Route;

Route::post('login', [UserController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('verify_token', [UserController::class, 'verifyToken']);
    Route::post('user-roles-list', [UserRoleController::class, 'userRoleList']);
    Route::post('permission-list', [UserRoleController::class, 'permissionList']);
    Route::post('user-role-save', [UserRoleController::class, 'userRoleSave']);
});