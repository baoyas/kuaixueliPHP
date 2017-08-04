<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;

class EducationController extends Controller
{
    public function level ()
    {
        return view('education/level');
    }

    public function info ()
    {
        return view('education/info');
    }
}
