<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfilePasswordRequest;
use App\Http\Requests\ProfileRequest;
use App\Services\ProfileService;
use CpsAuth;

class ProfileController extends Controller
{
    private $profile_service;

    public function __construct(ProfileService $profile_service)
    {
        $this->profile_service = $profile_service;
    }

    public function showList()
    {
        return view('rxjapan.profile.list')->with('staff', CpsAuth::user());
    }

    public function showEdit()
    {
        return view('rxjapan.profile.edit')->with('staff', CpsAuth::user());
    }

    public function actionUpdate(ProfileRequest $request)
    {
        $this->profile_service->updateProfile($request);

        return redirect(route('get_user_profile'))->with('flash_message', 'ユーザ情報の編集が完了しました。');
    }

    public function showEditPassword()
    {
        return view('rxjapan.profile.edit_password')->with('staff', CpsAuth::user());
    }

    public function actionUpdatePassword(ProfilePasswordRequest $request)
    {
        $this->profile_service->updatePassword($request);

        return redirect(route('get_user_profile'))->with('flash_message', 'パスワードが更新されました。');
    }
}
