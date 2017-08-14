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
        $province_id = $request->get('province_id', 0);
        $edu = Education::with('school.province')->with('provinces')->with('contacts')->where('level_1_id', $level_id)->orWhere('level_2_id', $level_id)->orWhere('level_3_id', $level_id)->get();
        $eLevel = EducationLevel::find($level_id);
        $provinces = [];
        foreach($edu as $e) {
            if($e->school->province) {
                $provinces[$e->school->province->id] = $e->school->province->name;
            }
        }
        if(!empty($province_id)) {
            $edu = Education::with(['school.province'=>function($query) use($province_id) {
                $query->where('id', $province_id);
            }])->with('contacts')->where('level_1_id', $level_id)->orWhere('level_2_id', $level_id)->orWhere('level_3_id', $level_id)->get();

        }
        $contacts = [];
        foreach ($edu as $e) {
            foreach($e->contacts as $c) {
                if($c->atype==1) {
                    $contacts = $c->toArray();
                }
            }
        }
        return view('education/level', ['edu'=>$edu, 'eLevel'=>$eLevel, 'provinces'=>$provinces, 'contacts'=>$contacts, 'province_id'=>$province_id]);
    }

    public function info (Request $request)
    {
        $education_id = $request->get('education_id');
        $edu = Education::with('school')->where(['id'=>$education_id])->first();
        return view('education/info', ['edu'=>$edu]);
    }
}
