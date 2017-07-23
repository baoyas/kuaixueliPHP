<?php

namespace App\Http\Controllers\Home;

use Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Content;

class ShareController extends Controller
{
    public function ldl (Request $request, $user_id)
    {
        //return view('reward/index')->header('Cache-Control', 'no-store');
        return Response::view('share/ldl')->header('Cache-Control', 'no-store');
    }
}