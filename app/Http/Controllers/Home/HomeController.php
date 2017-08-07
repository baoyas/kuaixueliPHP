<?php

namespace App\Http\Controllers\Home;

//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Model\EducationLevel;
class HomeController extends Controller
{
    public function index ()
    {
        $eLevel = EducationLevel::tree()->variables();
        $eLevel = $eLevel['items'];
        return view('home', ['eLevel'=>$eLevel]);
    }

}
