<?php

namespace App\Http\Controllers;

use App\Services\ExhibitionGroupService;
use CpsAuth;

class ExhibitionController extends Controller
{
    private $exhibition_group_service;

    public function __construct(ExhibitionGroupService $exhibition_group_service)
    {
        $this->exhibition_group_service = $exhibition_group_service;
    }

    /**
     * Exhibition List
     *
     * @return void
     */
    public function showList()
    {
        $exhibition_groups = $this->exhibition_group_service->getListAccessibleWithExhibition(CpsAuth::user())
            ->sortByDesc("exhibition_group_id");

        $banner_color = CpsAuth::bannerColor();
        $exhibition_group_id = $exhibition_groups->pluck('id')->first();

        return view('rxjapan.exhibition.list', compact([
            'exhibition_groups', 'banner_color', 'exhibition_group_id',
        ]));
    }
}
