<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class CityController extends Controller
{
    public function index()
    {
        return view('Client.index');
    }
}
