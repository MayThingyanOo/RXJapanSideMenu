<?php

namespace App\Services;

use App\Repositories\ProfileRepository;

class ProfileService
{
    private $profile_repository;

    public function __construct(ProfileRepository $profile_repository)
    {
        $this->profile_repository = $profile_repository;
    }

    public function updateProfile($request)
    {
        return $this->profile_repository->updateProfile($request);
    }

    public function updatePassword($request)
    {
        return $this->profile_repository->updatePassword($request);
    }
}
