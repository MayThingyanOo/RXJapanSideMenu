<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StaffMyPagePasswordRequest;
use App\Http\Requests\StaffMyPageRequest;
use App\Models\Staff;
use CpsAuth;
use DB;

class MypageController extends Controller
{
    public function showList()
    {
        return view('rxjapan.mypage.list')->with('staff', CpsAuth::user());
    }

    public function showEdit()
    {
        return view('rxjapan.mypage.edit')->with('staff', CpsAuth::user());
    }

    public function actionUpdate(StaffMyPageRequest $request)
    {
        $staff = CpsAuth::user();
        $staff->name = $request->name;
        $staff->department_name = $request->department_name; // staff cannot change for department
        $staff->save();
        return redirect(route('get_my_page_list'))->with('flash_message', 'ユーザ情報の編集が完了しました。');
    }

    public function showEditPassword()
    {
        return view('rxjapan.mypage.edit_password')->with('staff', CpsAuth::user());
    }

    public function actionUpdatePassword(StaffMyPagePasswordRequest $request)
    {
        DB::transaction(function () use ($request) {
            $staff_id = CpsAuth::id();
            $staff = Staff::findOrFail($staff_id);
            $staff->password = $request->password;
            $staff->save();
        });

        return redirect(route('get_my_page_list'))->with('flash_message', 'パスワードが更新されました。');
    }
}
