<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExhibitionController extends Controller
{
        /**
     * Exhibition List
     *
     * @return void
     */
    public function showList()
    {
        dd('list');
        return view('hello ex list');
    }
}
