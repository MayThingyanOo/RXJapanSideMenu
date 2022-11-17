<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\PasswordReminderRequest;
use App\Http\Requests\SendPasswordReminderMailRequest;
use App\Services\LoginService;
use App\Lib\CpsAuth\CpsAuth;
use CpsMail;

class LoginController extends Controller
{
    private $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function showLogin()
    {
        return view('rxjapan.auth.login');
    }

    public function actionLogin(LoginRequest $request)
    {
        $email = $request->email;
        $password = $request->password;

        $staff = $this->loginService->getStaff($email)->first();

        if ($staff) {
            $result = CpsAuth::setGuard("user_staff")
                    ->attempt(['email' => $email, 'password' => $password]);

            if (!$result) {
                return redirect(route('get_login'))
                ->withErrors(['email' => '認証エラー'])
                ->withInput();
            }
        } else {
            return redirect(route('get_login'))
                ->withErrors(['email' => '認証エラー'])
                ->withInput();
        }

        return redirect(route('get_exhibition_list'));
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

        $staff = $this->loginService->getStaff($email)->first();

        if ($staff) {
            $reminder = $this->loginService->createPasswordReminderHashByEmail($email);

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

        $this->loginService->remindPassword($hash, $password);

        return redirect(route('get_complete_pw_reminder'));
    }

    public function showCompletePasswordReminder()
    {
        return view('rxjapan.auth.complete');
    }
}
