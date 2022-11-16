<?php

namespace App\Services;

use App\Models\Staff;
use App\Models\StaffPasswordReminder;

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
}
