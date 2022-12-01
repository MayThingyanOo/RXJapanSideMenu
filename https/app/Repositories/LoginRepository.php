<?php

namespace App\Repositories;

use App\Models\Staff;
use App\Models\StaffPasswordReminder;
use Carbon\Carbon;

class LoginRepository
{
    private $staff;
    private $staff_password_reminder;

    public function __construct(Staff $staff, StaffPasswordReminder $staff_password_reminder)
    {
        $this->staff = $staff;
        $this->staff_password_reminder = $staff_password_reminder;
    }

    public function getStaff($email)
    {
        return $this->staff->where('email', $email)->where('is_super_user_flag', true);
    }

    public function create($staff_id, $hash)
    {
        $check_hash = $this->staff_password_reminder->pluck('hash')->toArray();

        while (in_array($hash, $check_hash)) {
            $hash = sha1(uniqid(mt_rand(), true));
        }

        return $this->staff_password_reminder->create([
            'staff_id' => $staff_id,
            'hash' => $hash,
            'created_by' => $staff_id,
        ]);
    }

    public function getByHashInStaffPasswordReminder($hash)
    {
        return $this->staff->whereHas("staffPasswordReminder", function ($query) use ($hash) {
            $query->where('hash', '=', $hash);
        });
    }

    public function remindPassword($staff_id, $password)
    {
        $staff = $this->staff->find($staff_id);
        $staff->update(['password' => $password, 'staff_pwd_reset_flag' => false]);
        return $staff;
    }

    public function setUsed($staff_id, $hash)
    {
        $staff_password_reminder = $this->staff_password_reminder
            ->where('staff_id', $staff_id)
            ->where('hash', $hash)
            ->first();

        $staff_password_reminder->update([
            'is_used' => true,
            'reset_at' => Carbon::now(),
        ]);

        return $staff_password_reminder;
    }

    public function changePassword($password, $staff_id)
    {
        $staff = $this->staff->find($staff_id);
        $staff->update(['password' => $password, 'staff_pwd_reset_flag' => false]);
        return $staff;
    }
}
