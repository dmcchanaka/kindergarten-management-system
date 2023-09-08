<?php

use App\Http\Controllers\API\V1\OrganizationController;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\V1\UserRoleController;
use Illuminate\Support\Facades\Route;

Route::post('login', [UserController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('user-roles-list', [UserRoleController::class, 'userRoleList']);
    
    
});
Route::prefix('organization')->group(function () {
    Route::get('/list', [OrganizationController::class, 'index'])->name('organization-list');
    Route::post('/create', [OrganizationController::class, 'create'])->name('organization-create');
    Route::delete('/delete/{id}', [OrganizationController::class, 'delete'])->name('organization-delete');
    Route::post('/update/{id}', [OrganizationController::class, 'update'])->name('organization-update');
    Route::get('/find/{id}', [OrganizationController::class, 'find'])->name('organization-find');
});
