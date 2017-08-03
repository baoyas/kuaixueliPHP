<?php

namespace App\Http\Controllers\Home;

//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class HomeController extends Controller
{
    public function index ()
    {
        return view('home');
    }

}
