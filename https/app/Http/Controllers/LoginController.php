<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\PasswordReminderRequest;
use App\Http\Requests\SendPasswordReminderMailRequest;
use App\Services\LoginService;
use CpsAuth;
use CpsMail;
use Hash;
use Session;

class LoginController extends Controller
{
    private $login_service;

    public function __construct(LoginService $login_service)
    {
        $this->login_service = $login_service;
    }

    public function showLogin()
    {
        if (!CpsAuth::setGuard("user_staff")->isGuest()) {
            return redirect(route('get_exhibition_list'));
        }

        Session::forget('staff');

        return view('rxjapan.auth.login');
    }

    public function actionLogin(LoginRequest $request)
    {
        $email = $request->email;
        $password = $request->password;

        $staff = $this->login_service->getStaff($email);

        if ($staff->staff_pwd_reset_flag && Hash::check($password, $staff->password)) {
            Session::put("staff", $staff);
            return redirect(route('get_change_password'));
        }

        $result = $this->checkAuth($email, $password);

        if (!$result) {
            return redirect(route('get_login'))
                ->withErrors(['email' => '認証エラー'])
                ->withInput();
        }

        return redirect(route('get_exhibition_list'));
    }

    private function checkAuth($email, $password)
    {
        return CpsAuth::setGuard("user_staff")->attempt([
            'email' => $email,
            'password' => $password,
        ]);
    }

    public function showReminderMailForm()
    {
        return view('rxjapan.auth.password');
    }

    public function actionSendPasswordReminderMail(SendPasswordReminderMailRequest $request)
    {
        $email = $request->email;
        if (str_contains($email, "'")) {
            return redirect(route('get_show_password'))
                ->withErrors(['email' => '正しいメールアドレスを入力してください。'])
                ->withInput();
        }

        $staff = $this->login_service->getStaff($email);

        if ($staff) {
            $reminder = $this->login_service->createPasswordReminderHashByEmail($email);

            $body = view("rxjapan.email.forget_password", ['hash' => $reminder->hash]);
            CpsMail::mailTo($email, "【Q-business】ログインパスワード再設定の確認", $body);
        }
        return redirect(route('get_finish'));
    }

    public function showFinish()
    {
        return view('rxjapan.auth.finish');
    }

    public function showPasswordReminderForm(PasswordReminderRequest $request)
    {
        $hash = $request->get('h');

        return view('rxjapan.auth.reminder')->with('hash', $hash);
    }

    public function actionRemindPassword(PasswordReminderRequest $request)
    {
        $hash = $request->hash;
        $password = $request->password;

        $this->login_service->remindPassword($hash, $password);

        return redirect(route('get_complete_pw_reminder'));
    }

    public function showCompletePasswordReminder()
    {
        return view('rxjapan.auth.complete');
    }

    public function logout()
    {
        CpsAuth::logout();

        return redirect(route("get_login"));
    }

    public function showChangePassword()
    {
        if (Session::get('staff') == '') {
            return redirect(route('get_login'));
        } else {
            return view('rxjapan.auth.change_password');
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $staff = Session::get('staff');
        $staff_id = $staff->staff_id;
        $password = $request->new_password;

        $this->login_service->changePassword($password, $staff_id);

        $result = $this->checkAuth($staff->email, $password);

        if (!$result) {
            return redirect(route('get_login'))
                ->withErrors(['email' => '認証エラー'])
                ->withInput();
        }

        Session::forget('staff');

        return redirect(route('get_exhibition_list'))->with('flash_message', 'パスワードが更新されました。');
    }
}
