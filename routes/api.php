<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\MedicalRecordController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\Admin\DepartmentController as AdminDepartmentController;
use App\Http\Controllers\Api\Admin\DoctorController as AdminDoctorController;
use App\Http\Controllers\Api\Admin\UploadController;
use App\Http\Controllers\Api\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Api\Admin\MedicalRecordController as AdminMedicalRecordController;
use App\Http\Controllers\Api\Admin\AnnouncementController as AdminAnnouncementController;



/*
|--------------------------------------------------------------------------
| 公共接口（不需要登录）
|--------------------------------------------------------------------------
*/

// 用户
Route::post('/register',[UserController::class,'register']);
Route::post('/login',[UserController::class,'login']);

// 科室
Route::get('/departments', [DepartmentController::class, 'index']);
Route::get('/departments/{id}', [DepartmentController::class, 'show']);

// 医生
Route::get('/doctors', [DoctorController::class, 'index']);
Route::get('/doctors/{id}', [DoctorController::class, 'show']);

// 排班
Route::get('/schedules', [ScheduleController::class, 'index']);

// 公告
Route::get('/announcements',[AnnouncementController::class,'index']);
Route::get('/announcements/{id}',[AnnouncementController::class,'show']);

/*
|--------------------------------------------------------------------------
| 需要登录（token）
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->post('/logout',[UserController::class,'logout']);
Route::middleware('auth:sanctum')->group(function () {

    // 当前用户
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

	Route::post('/user/update-profile', [UserController::class,'updateProfile']);
 	Route::post('/user/change-password', [UserController::class,'changePassword']);


// 预约
Route::post('/appointments', [AppointmentController::class, 'store']);
Route::get('/my-appointments', [AppointmentController::class, 'myAppointments']);
Route::get('/appointments/{id}', [AppointmentController::class, 'detail']);
Route::delete('/appointments/{id}', [AppointmentController::class, 'cancel']);
// Route::post('/appointments/cancel/{id}', [AppointmentController::class, 'cancel']);

    // 评价
    Route::post('/reviews',[ReviewController::class,'store']);
    Route::get('/doctors/{id}/reviews',[ReviewController::class,'doctorReviews']);

    // 通知
    Route::get('/notifications',[NotificationController::class,'index']);
    Route::put('/notifications/{id}/read',[NotificationController::class,'read']);

    // 就诊记录
    Route::get('/medical-records',[MedicalRecordController::class,'index']);

});

/*
|--------------------------------------------------------------------------
| 管理员接口
|--------------------------------------------------------------------------
*/

// 登录
Route::post('/admin/login',[AdminController::class,'login']);

Route::middleware('auth:sanctum')->prefix('admin')->group(function(){

    Route::get('/info',[AdminController::class,'info']);
    Route::post('/logout',[AdminController::class,'logout']);

    // 用户管理
    Route::get('/users', [AdminUserController::class,'index']);
    Route::get('/users/{id}', [AdminUserController::class,'show']);
    Route::put('/users/{id}', [AdminUserController::class,'update']);
    Route::delete('/users/{id}', [AdminUserController::class,'destroy']);
    Route::post('/users/{id}/reset-password', [AdminUserController::class,'resetPassword']);


 // 科室管理
    Route::get('/departments', [AdminDepartmentController::class,'index']);
    Route::post('/departments', [AdminDepartmentController::class,'store']);
    Route::delete('/departments/{id}', [AdminDepartmentController::class,'destroy']);
Route::put('/departments/{id}', [AdminDepartmentController::class,'update']);

 Route::get('/doctors', [AdminDoctorController::class,'index']);
    Route::post('/doctors', [AdminDoctorController::class,'store']);
    Route::put('/doctors/{id}', [AdminDoctorController::class,'update']);
    Route::delete('/doctors/{id}', [AdminDoctorController::class,'destroy']);

Route::post('/upload', [UploadController::class,'upload']);

Route::get('/appointments', [AdminAppointmentController::class, 'index']);
Route::get('/appointments/{id}', [AdminAppointmentController::class, 'show']);
Route::put('/appointments/{id}/status', [AdminAppointmentController::class, 'updateStatus']);
Route::delete('/appointments/{id}', [AdminAppointmentController::class, 'destroy']);

Route::get('/dashboard/statistics', [DashboardController::class, 'statistics']);
Route::get('/dashboard/trend', [DashboardController::class, 'trend']);

Route::get('/schedules', [AdminScheduleController::class,'index']);
Route::post('/schedules', [AdminScheduleController::class,'store']);
Route::delete('/schedules/{id}', [AdminScheduleController::class,'destroy']);
Route::put('/schedules/{id}', [AdminScheduleController::class,'update']);

Route::get('/medical-records', [AdminMedicalRecordController::class,'index']);
Route::post('/medical-records', [AdminMedicalRecordController::class,'store']);
Route::delete('/medical-records/{id}', [AdminMedicalRecordController::class,'destroy']);
Route::get('/medical-records/{id}', [AdminMedicalRecordController::class,'show']);

Route::get('/announcements', [AdminAnnouncementController::class,'index']);
Route::post('/announcements', [AdminAnnouncementController::class,'store']);
Route::put('/announcements/{id}', [AdminAnnouncementController::class,'update']);
Route::delete('/announcements/{id}', [AdminAnnouncementController::class,'destroy']);
Route::get('/announcements/{id}', [AdminAnnouncementController::class,'show']);

});