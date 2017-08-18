<?php

namespace App\Http\Controllers\Home;

use Cache;
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
        $edu = Education::with('school.province')->with('contacts')->where('level_1_id', $level_id)->orWhere('level_2_id', $level_id)->orWhere('level_3_id', $level_id)->get();
        $eLevel = EducationLevel::find($level_id);
        $provinces = [];
        foreach($edu as $e) {
            if($e->school->province) {
                $provinces[$e->school->province->id] = $e->school->province->name;
            }
        }
        if(!empty($province_id)) {
            $edu = Education::with('school.province')->with('contacts')->whereIn('school_id', function($query) use($province_id) {
                $query->select('id')->from('education_school')->where('province_id', $province_id);
            })->where('level_1_id', $level_id)->orWhere('level_2_id', $level_id)->orWhere('level_3_id', $level_id)->get();
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

    public function cart (Request $request)
    {
        $education_id = $request->get('education_id', 0);
        $user_id = $request->user()->id;
        if($user_id) {
            $cacheKey = "cart_{$user_id}";
            $cart = Cache::get($cacheKey);
            $cart = empty($cart) ? [] : \json_decode($cart, true);
            if($education_id) {
                $cart[$education_id] = 1;
                Cache::put($cacheKey, \json_encode($cart), 365*60*24);
            }
        } else {
            $cart = $request->session()->get('cart');
            $cart = empty($cart) ? [] : \json_decode($cart, true);
            if($education_id) {
                $cart[$education_id] = 1;
                $request->session()->put('cart', \json_encode($cart));
            }
        }
        $edus = Education::with('school.province')->whereIn('id', array_keys($cart))->get();
        return view('education/cart', ['edus'=>$edus]);
    }
}
