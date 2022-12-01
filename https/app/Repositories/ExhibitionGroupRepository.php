<?php

namespace App\Repositories;

use App\Models\ExhibitionGroup;

class ExhibitionGroupRepository
{
    public function getListAccessibleWithExhibition($staff)
    {
        return ExhibitionGroup::with('exhibitions.exhibitionDates')->where('user_id', $staff->user_id);
    }
}
