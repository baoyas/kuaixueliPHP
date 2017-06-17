<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Content;

class ContentController extends Controller
{
    public function index (Request $request, $id=0)
    {
        $content = Content::where(['id'=>$id])->first();
        if(empty($content)) {
            return;
        }
        echo $content->richtext;
    }
}