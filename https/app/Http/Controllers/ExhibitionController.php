<?php

namespace App\Http\Controllers;

use App\Services\ExhibitionGroupService;
use App\Lib\CpsAuth\CpsAuth;
use Illuminate\Support\Arr;
use Route;
use Illuminate\Support\Facades\Auth;

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
        $staff = Auth::guard('user_staff')->user();
        $exhibition_groups = $this->exhibition_group_service->getListAccessibleWithExhibition($staff)
                                  ->sortByDesc("exhibition_group_id");
        $exhibitions = $exhibition_groups->pluck('exhibitions')->collapse();

        $vRequest = \Request::create(\URL::previous());
        $preRoute = Arr::first(Route::getRoutes(), lambda(compact('vRequest'), '$r=>$r->matches($vRequest)')) ?: Route::current();
        $preRoute->bind($vRequest);
        if ($preRoute->hasParameter('exhibition_id')) {
            $open = $exhibitions->pluck('exhibition_group_id', 'id')->get($preRoute->parameter('exhibition_id'));
        } else {
            $open = $preRoute->parameter('exhibition_group_id', request('tab'));
        }

        return view('rxjapan.exhibition.list', compact(['exhibition_groups', 'open']));
    }
}
