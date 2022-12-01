<?php

namespace App\Repositories;

use App\Models\Staff;
use CpsAuth;
use DB;

class ProfileRepository
{
    public function updateProfile($request)
    {
        $staff = CpsAuth::user();
        $staff->name = $request->name;
        $staff->department_name = $request->department_name;
        $staff->save();
    }

    public function updatePassword($request)
    {
        DB::transaction(function () use ($request) {
            $staff_id = CpsAuth::id();
            $staff = Staff::findOrFail($staff_id);
            $staff->password = $request->password;
            $staff->save();
        });
    }
}
