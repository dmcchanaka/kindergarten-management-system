<?php

use App\Http\Controllers\API\V1\OrganizationController;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\V1\UserRoleController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\GeneralSettingsController;
use Illuminate\Support\Facades\Route;

Route::post('login', [UserController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('verify_token', [UserController::class, 'verifyToken']);
    Route::post('user-roles-list', [UserRoleController::class, 'userRoleList']);
    
    Route::prefix('organization')->group(function () {
        Route::get('/list', [OrganizationController::class, 'index'])->name('organization-list');
        Route::post('/create', [OrganizationController::class, 'create'])->name('organization-create');
        Route::delete('/delete/{id}', [OrganizationController::class, 'delete'])->name('organization-delete');
        Route::post('/update/{id}', [OrganizationController::class, 'update'])->name('organization-update');
        Route::get('/find/{id}', [OrganizationController::class, 'find'])->name('organization-find');
    });
    Route::get('role-user/{role}', [UserController::class, 'getUsers'])->name('get-role-users');

    Route::post('permission-list', [UserRoleController::class, 'permissionList']);
    Route::post('user-role-save', [UserRoleController::class, 'userRoleSave']);
    Route::post('user-role-update', [UserRoleController::class, 'userRoleUpdate']);

    //user management
    Route::post('users-list', [UserController::class, 'usersList']);
    Route::post('user-registration', [UserController::class, 'userRegistration']);

    //general settings
    Route::post('save-logo', [GeneralSettingsController::class, 'saveLogo']);
    Route::post('fetch-general-settings', [GeneralSettingsController::class, 'fetchGeneralSettings']);
    Route::post('save-ui-settings', [GeneralSettingsController::class, 'saveUiSettings']);

    //class room
    Route::post('teachers-list', [ClassRoomController::class, 'teachersList']);
    Route::post('class-room-registration', [ClassRoomController::class, 'classRoomRegistration']);
});
