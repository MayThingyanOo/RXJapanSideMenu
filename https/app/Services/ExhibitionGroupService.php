<?php

namespace App\Services;

use App\Models\ExhibitionGroup;

class ExhibitionGroupService
{
    /**
     * @param $user_staff
     * @return mixed
     */
    public function getListAccessibleWithExhibition($staff)
    {
        if ($staff->is_super_user_flag) {
            return ExhibitionGroup::with('exhibitions.exhibition_dates')->where('user_id', $staff->user_id)->get();
        }
    }

    /**
     * @param $exhibition_group_id
     * @return mixed
     */
    public function getById($exhibition_group_id)
    {
        return ExhibitionGroup::find($exhibition_group_id);
    }

    public function forceDeleteById($exhibition_group_id)
    {
        return ExhibitionGroup::find($exhibition_group_id)->forceDelete();
    }
}
