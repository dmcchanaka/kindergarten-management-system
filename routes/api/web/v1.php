<?php

use App\Http\Controllers\API\V1\ChatController;
use App\Http\Controllers\API\V1\AttendanceController;
use App\Http\Controllers\API\V1\OrganizationController;
use App\Http\Controllers\API\V1\ParentController;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\V1\UserRoleController;
use App\Http\Controllers\API\V1\ClassRoomController;
use App\Http\Controllers\API\V1\DashboardController;
use App\Http\Controllers\API\V1\GalleryController;
use App\Http\Controllers\API\V1\GeneralSettingsController;
use App\Http\Controllers\API\V1\StudentController;
use Illuminate\Support\Facades\Route;

Route::post('login', [UserController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('verify_token', [UserController::class, 'verifyToken']);
    Route::post('user-roles-list', [UserRoleController::class, 'userRoleList']);

    Route::post('overview', [DashboardController::class, 'overview']);
    
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
    Route::post('user-update', [UserController::class, 'userUpdate']);
    Route::post('user-profile-update', [UserController::class, 'userProfileUpdate']);
    Route::post('user-profile-password-update', [UserController::class, 'userProfilePasswordUpdate']);
    Route::post('user-logo-update', [UserController::class, 'userLogoUpdate']);

    //general settings
    Route::post('save-logo', [GeneralSettingsController::class, 'saveLogo']);
    Route::post('fetch-general-settings', [GeneralSettingsController::class, 'fetchGeneralSettings']);
    Route::post('save-ui-settings', [GeneralSettingsController::class, 'saveUiSettings']);

    //class room
    Route::post('organization-list', [ClassRoomController::class, 'organizationList']);
    Route::post('teachers-list', [ClassRoomController::class, 'teachersList']);
    Route::post('class-room-registration', [ClassRoomController::class, 'classRoomRegistration']);
    Route::post('class-room-list', [ClassRoomController::class, 'classRoomList']);
    Route::post('class-room-update', [ClassRoomController::class, 'classRoomUpdate']);
    Route::post('class-room-remove', [ClassRoomController::class, 'classRoomRemove']);

    //parents
    Route::post('class-room-list-associate-with-organization', [ParentController::class, 'classRoomsAssociatedWithOrganization']);
    Route::post('parent-registration', [ParentController::class, 'parentRegistration']);
    Route::post('parents-list', [ParentController::class, 'fetchParentsList']);
    Route::post('update-parent', [ParentController::class, 'updateParent']);
    Route::post('parent-remove', [ParentController::class, 'parentRemove']);

    //students
    Route::post('parents-lookup', [StudentController::class, 'parentLookUp']);
    Route::post('student-registration', [StudentController::class, 'studentRegistration']);
    Route::post('students-list', [StudentController::class, 'fetchStudentsList']);
    Route::post('update-student', [StudentController::class, 'updateStudent']);
    Route::post('student-remove', [StudentController::class, 'studentRemove']);

    //content area
    Route::post('student-list-associate-with-class-room', [GalleryController::class, 'studentListAssociateWithClassRoom']);
    Route::post('gallery-registration', [GalleryController::class, 'galleryRegistration']);
    Route::post('gallery-update', [GalleryController::class, 'galleryUpdate']);
    Route::post('gallery-remove', [GalleryController::class, 'galleryRemove']);

    Route::post('content-list', [GalleryController::class, 'fetchContentList']);

    //attendance
    Route::post('attendance-list', [AttendanceController::class, 'fetchAttendanceList']);
    
    //chat
    Route::post('chat-user-list', [ChatController::class, 'chatUserList']);
    Route::post('user-messages', [ChatController::class, 'userMessages']);
    Route::post('user-old-messages', [ChatController::class, 'userOldMessages']);
    Route::post('send-message', [ChatController::class, 'sendMessage']);
    Route::post('update-message-seen', [ChatController::class, 'updateMessageSeen']);
});

//unauth access routes
Route::post('lookup-organization-list', [AttendanceController::class, 'lookupOrganizationList']);
Route::post('lookup-class-room-list', [AttendanceController::class, 'lookupClassRoomList']);
Route::post('lookup-class-room-student-list', [AttendanceController::class, 'lookupClassRoomStudentList']);

Route::post('fetch-student-attendance-list', [AttendanceController::class, 'fetchStudentAttendanceList']);
Route::post('all-students-list', [AttendanceController::class, 'fetchStudentsList']);
Route::post('mark-student-attendance', [AttendanceController::class, 'markStudentAttendance']);
Route::post('approve-student-attendance', [AttendanceController::class, 'approveStudentAttendance']);
