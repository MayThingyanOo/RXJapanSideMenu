<?php

namespace App\Services;

use App\Repositories\LoginRepository;
use DB;

class LoginService
{
    private $login_repository;

    public function __construct(LoginRepository $login_repository)
    {
        $this->login_repository = $login_repository;
    }

    public function getStaff($email)
    {
        return $this->login_repository->getStaff($email)->first();
    }

    public function createPasswordReminderHashByEmail($email)
    {
        $hash = sha1(uniqid(mt_rand(), true));
        $staff = $this->login_repository->getStaff($email)->first();

        return $this->login_repository->create($staff->staff_id, $hash);
    }

    public function remindPassword($hash, $password)
    {
        $staff = $this->login_repository->getByHashInStaffPasswordReminder($hash)->first();

        if (empty($staff)) {
            Abort(404);
        }

        DB::transaction(function () use ($hash, $password, $staff) {
            $staff_id = $staff->staff_id;

            $staff = $this->login_repository->remindPassword($staff_id, $password);

            $this->login_repository->setUsed($staff_id, $hash);
        });
    }

    public function changePassword($password, $staff_id)
    {
        $staff = $this->login_repository->changePassword($password, $staff_id);
    }
}
