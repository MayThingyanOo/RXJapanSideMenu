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
        });
    });
});

Route::controller(ExhibitionController::class)->group(function () {
    Route::get('/user/exhibition', 'showList')->name('get_exhibition_list', 'イベント一覧');
});
