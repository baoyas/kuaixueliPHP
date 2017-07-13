<?php

namespace App\Http\Controllers\Home;

use Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Content;

class RewardController extends Controller
{
    public function index (Request $request)
    {
        //return view('reward/index')->header('Cache-Control', 'no-store');
        return Response::view('reward/index')->header('Cache-Control', 'no-store');
    }
}