<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Education;
use App\Model\EducationLevel;

class EducationController extends Controller
{
    public function level (Request $request)
    {
        $level_id = $request->get('level_id');
        $edu = Education::with('school')->where('level_1_id', $level_id)->orWhere('level_2_id', $level_id)->orWhere('level_3_id', $level_id)->get();
        $eLevel = EducationLevel::find($level_id);
        return view('education/level', ['edu'=>$edu, 'eLevel'=>$eLevel]);
    }

    public function info (Request $request)
    {
        $education_id = $request->get('education_id');
        $edu = Education::with('school')->where(['id'=>$education_id])->first();
        return view('education/info', ['edu'=>$edu]);
    }
}
