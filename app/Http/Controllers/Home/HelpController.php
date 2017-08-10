<?php

namespace App\Http\Controllers\Home;

use Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Content;

class HelpController extends Controller
{
    public function about (Request $request)
    {
        return Response::view('help/about');
    }
}