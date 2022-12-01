<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::prefix('user')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'showLogin')->name('get_login');
        Route::post('/login', 'actionLogin')->name('action_login');
        Route::prefix('password')->group(function () {
            Route::get('', 'showReminderMailForm')->name('get_show_password');
            Route::post('', 'actionSendPasswordReminderMail')->name('action_send_reminder_mail');
            Route::get('/finish', 'showFinish')->name('get_finish');
            Route::get('/reminder', 'showPasswordReminderForm')->name('get_reminder_password');
            Route::post('/reminder', 'actionRemindPassword')->name('action_remind_password');
            Route::get('/reminder/complete', 'showCompletePasswordReminder')->name('get_complete_pw_reminder');
        });
        Route::get('/change_password', 'showChangePassword')->name('get_change_password');
        Route::post('/change_password', 'changePassword')->name('action_change_password');
    });

    Route::group(['middleware' => 'auth:user_staff'], function () {
        Route::controller(LoginController::class)->group(function () {
            Route::get('/logout', 'logout')->name('get_logout');
        });
        Route::controller(ExhibitionController::class)->group(function () {
            Route::get('/exhibition', 'showList')->name('get_exhibition_list');
        });
        Route::controller(ProfileController::class)->group(function () {
            Route::prefix('profile')->group(function () {
                Route::get('', 'showList')->name('get_user_profile');
                Route::get('/edit', 'showEdit')->name('get_profile_edit');
                Route::post('/update', 'actionUpdate')->name('action_profile_update');
                Route::get('/password/edit', 'showEditPassword')->name('get_edit_password_form');
                Route::post('/password/update', 'actionUpdatePassword')->name('action_update_password');
            });
        });
    });
});
