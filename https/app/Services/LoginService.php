<?php

namespace App\Services;

use App\Models\Staff;
use App\Models\StaffPasswordReminder;
use Carbon\Carbon;

class LoginService
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

    public function createPasswordReminderHashByEmail($email)
    {
        $hash = sha1(uniqid(mt_rand(), true));
        $staff = $this->getByEmail($email);

        return $this->create($staff->staff_id, $hash);
    }

    public function getByEmail($email)
    {
        $staff = $this->staff->where('email', $email)->first();

        if (empty($staff)) {
            Abort(404);
        }

        return $staff;
    }

    public function create($staff_id, $hash)
    {
        $check_hash = $this->staff_password_reminder->pluck('hash')->toArray();

        while (in_array($hash, $check_hash)) {
            $hash = sha1(uniqid(mt_rand(), true));
        }

        return $this->staff_password_reminder->create([
            'staff_id' => $staff_id, 'hash' => $hash,
            'created_by' => $staff_id
        ]);
    }

    public function remindPassword($hash, $password)
    {
        $staff = $this->getByHashInStaffPasswordReminder($hash);

        \DB::transaction(function () use ($hash, $password, $staff) {

            $staff = $this->updateremindPassword($staff->staff_id, $password);

            $this->setUsed($staff->staff_id, $hash);
        });
    }

    public function getByHashInStaffPasswordReminder($hash)
    {
        $staff = $this->staff->whereHas("staffPasswordReminder", function ($query) use ($hash) {
            $query->where('hash', '=', $hash);
        })
            ->first();

        if (empty($staff)) {
            Abort(404);
        }

        return $staff;
    }

    public function updateremindPassword($staff_id, $password)
    {
        $staff = $this->staff->find($staff_id);
        $staff->update(['password' => $password, 'staff_pwd_reset_flag' => false]);

        return $staff;
    }

    public function setUsed($staff_id, $hash)
    {
        $staff_password_reminder = $this->staff_password_reminder->where('staff_id', $staff_id)
                                        ->where('hash', $hash)->first();
        $staff_password_reminder->update(['is_used' => true, 'reset_at' => Carbon::now()]);

        return $staff_password_reminder;
    }
}
