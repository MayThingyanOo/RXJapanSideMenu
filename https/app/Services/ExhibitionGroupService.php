<?php

namespace App\Services;

use App\Repositories\ExhibitionGroupRepository;

class ExhibitionGroupService
{
    private $ex_gp_repository;

    public function __construct(ExhibitionGroupRepository $ex_gp_repository)
    {
        $this->ex_gp_repository = $ex_gp_repository;
    }

    /**
     * @param $user_staff
     * @return mixed
     */
    public function getListAccessibleWithExhibition($staff)
    {
        if ($staff->is_super_user_flag) {
            return $this->ex_gp_repository->getListAccessibleWithExhibition($staff)->get();
        }
    }
}
