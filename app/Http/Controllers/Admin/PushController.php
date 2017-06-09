<?php

namespace App\Http\Controllers\Admin;

use App\Model\Push;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use JPush\Client as JPush;

class PushController extends Controller
{
    /**
     * GET|HEAD                       | admin/push                   | push.index
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.push.index');
    }

    /**
     * GET|HEAD                       | admin/push/create            | push.create
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * POST                           | admin/push                   | push.store
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = Input::except('_token');
        $rules = [
            'push_title' => 'required',
            'push_content' => 'required',
            'push_model' => 'required',
        ];
        $message = [
            'push_title.required' => '推送标题不能为空！',
            'push_content.required' => '推送内容不能为空！',
            'push_model.required' => '目标不能为空！'
        ];
        if ($input['push_model'] == 1)
        {
            $input['push_model'] = 'IOS全部用户';
            $Platform = 'ios';
        }
        else
        {
            $input['push_model'] = 'Android全部用户';
            $Platform = 'android';
        }
        $validator = Validator::make($input, $rules, $message);
        if ($validator->passes())
        {
            $re = Push::create($input);
            if ($re)
            {
                /**
                 * 推送
                 */
                $app_key = Config::get('web.JIGUANG_Ak');
                $master_secret = Config::get('web.JIGUANG_MK');
                $client = new JPush($app_key, $master_secret);
                if ($Platform == 'ios')
                {
                    $options = array(
                         "apns_production"=>true, 
                    );
                    $dd = $client->push()
                        ->setPlatform($Platform)
                        ->addAllAudience()
//                    ->setNotificationAlert($input['push_content'])
                        ->iosNotification($input['push_content'], array(
                            'sound' => 'hello jpush',
                            'badge' => 1,
                            'content-available' => true,
                            'category' => 'jiguang',
                            'extras' => array(
                                'key' => 'value',
                                'jiguang'
                            ),
                        ))
                        ->options($options)
                        ->send();
                }
                else
                {
                    $dd = $client->push()
                        ->setPlatform($Platform)
                        ->addAllAudience()
//                    ->setNotificationAlert($input['push_content'])
                        ->androidNotification($input['push_content'], array(
                            'title' => $input['push_title'],
                            'builder_id' => 2,
                            'extras' => array(
                                'key' => 'value',
                                'jiguang'
                            ),
                        ))
                        ->send();
                }
                return redirect('admin/index');
            }
            else
            {
                return back()->with('errors', '推送失败！');
            }
        }
        else
        {
            return back()->withErrors($validator);
        }
    }

    /**
     * GET|HEAD                       | admin/push/{push}            | push.show
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * GET|HEAD                       | admin/push/{push}/edit       | push.edit
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * PUT|PATCH                      | admin/push/{push}            | push.update
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * DELETE                         | admin/push/{push}            | push.destroy
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
