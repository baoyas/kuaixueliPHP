<?php

namespace App\Verification;
use App\Helpers\Helpers;
use App\Http\Controllers\Api\ResultController as Result;
use App\Model\Send;
use App\Model\User;
use Illuminate\Http\Request;
use App\Lib\JassEasemob;
use Illuminate\Support\Facades\Input;

/**
 * Class SendVerification
 *
 * @package \App\Verification
 */
class SendVerification
{
    public function __construct ()
    {
        $this->result = new Result();
        $this->jasseasemob = new JassEasemob();
    }

    /**
     * 群发 - 用户
     * @param Request $request
     */
    public function sendUsers (Request $request)
    {
        $uid = $request->item['uid'];
        $username = $request->item['username'];
        $userInfo = User::where('id', $uid)->first();
        $now_time = Helpers::getToday(date('Y-m-d', time())); //今天的开始时间和结束时间
        $content = $request->get('send_content');
        $users = $request->get('send_users');
        $groups = $request->get('send_group_id');
        $send_type = $request->get('send_type');
        $file = $request->get('file');
        /**
         * 判断用户每天发送的次数
         */
        $send = Send::where('send_time', '>=', $now_time['start'])->where('send_time', '<', $now_time['end'])->where('send_form_uid', $uid)->count();
        /**
         * 判断每人每日发送的次数
         */
        if ($send >= config('web.TODAY_SEND_NUM'))
        {
            return $this->result->setStatusMsg('error')->setStatusCode(404)->setMessage('发送次数已经上限，请明天在发送！')->responseError();
        }
        /**
         * 判断用户和群是否为空
         */
        if ($users == '' && $groups == '')
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('用户或群组不能同时为空！')->responseError();
        }
        $users = ($users != '') ? $users : null;
        $groups = ($groups != '') ? $groups : null;
        switch ($send_type) {
            case 'txt':
                $save = [
                    'send_form_uid' => $uid,
                    'send_form_username' => $username,
                    'send_content' => $content,
                    'send_users' => $users,
                    'send_group_id' => $groups,
                    'send_time' => time()
                ];
                $statues = Send::create($save);
                if ($statues)
                {
                    if ($users != '')
                    {
                        $ext = [
                            'nickname' => $userInfo->nickname,
                            'user_face' => config('web.QINIU_URL').'/'.$userInfo->user_face
                        ];
                        $this->jasseasemob->yy_hxSend($username, explode(',', $users), $content, 'users', $ext);
                    }
                    else
                    {
                        $ext = [
                            'nickname' => $userInfo->nickname,
                            'user_face' => config('web.QINIU_URL').'/'.$userInfo->user_face
                        ];
                        $this->jasseasemob->yy_hxSend($username, explode(',', $groups), $content, 'chatgroups', $ext);
                    }
                    User::addPoints($uid, config('web.SENDUSERS_POINTS'));
                    return $this->result->responses([
                        'status' => 'success',
                        'status_code' => '',
                        'message' => '发送成功！'
                    ]);
                }
                else
                {
                    return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('发送失败！')->responseError();
                }
                break;
            case 'img':
                $dd = $this->uploadFile($file, $send_type);
                if ($dd == false)
                {
                    return $this->result->setStatusMsg('error')->setStatusCode(502)->setMessage('发送的文件大小请在1M以下！')->responseError();
                }
                $content = json_encode($dd);
                $save = [
                    'send_form_uid' => $uid,
                    'send_form_username' => $username,
                    'send_content' => $content,
                    'send_users' => $users,
                    'send_group_id' => $groups,
                    'send_time' => time()
                ];
                $statues = Send::create($save);
                if ($statues)
                {
                    if ($users != '')
                    {
                        $ext = [
                            'nickname' => $userInfo->nickname,
                            'user_face' => config('web.QINIU_URL').'/'.$userInfo->user_face
                        ];
                        $this->jasseasemob->yy_hxSend_img($username, explode(',', $users),$dd['uri'].'/'.$dd['entities'][0]['uuid'], $file, $dd['entities'][0]['share-secret'], $dd['width'], $dd['height'], 'users', $ext);
                    }
                    else
                    {
                        $ext = [
                            'nickname' => $userInfo->nickname,
                            'user_face' => config('web.QINIU_URL').'/'.$userInfo->user_face
                        ];
                        $this->jasseasemob->yy_hxSend_img($username, explode(',', $groups),$dd['uri'].'/'.$dd['entities'][0]['uuid'], $file, $dd['entities'][0]['share-secret'], $dd['width'], $dd['height'], 'chatgroups', $ext);
                    }
                    User::addPoints($uid, config('web.SENDUSERS_POINTS'));
                    return $this->result->responses([
                        'status' => 'success',
                        'status_code' => '',
                        'message' => '发送成功！'
                    ]);
                }
                else
                {
                    return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('发送失败！')->responseError();
                }
                break;
            case 'audio':
                $dd = $this->uploadFile($file, $send_type);
                if ($dd == false)
                {
                    return $this->result->setStatusMsg('error')->setStatusCode(502)->setMessage('发送的文件大小请在1M以下！')->responseError();
                }
                $content = json_encode($dd);
                $save = [
                    'send_form_uid' => $uid,
                    'send_form_username' => $username,
                    'send_content' => $content,
                    'send_users' => $users,
                    'send_group_id' => $groups,
                    'send_time' => time()
                ];
                $statues = Send::create($save);
                if ($statues)
                {
                    if ($users != '')
                    {
                        $ext = [
                            'nickname' => $userInfo->nickname,
                            'user_face' => config('web.QINIU_URL').'/'.$userInfo->user_face
                        ];
                        $this->jasseasemob->yy_hxSend_audio($username, explode(',', $users),$dd['uri'].'/'.$dd['entities'][0]['uuid'], $file, $dd['size'], $dd['entities'][0]['share-secret'], 'users', $ext);
                    }
                    else
                    {
                        $ext = [
                            'nickname' => $userInfo->nickname,
                            'user_face' => config('web.QINIU_URL').'/'.$userInfo->user_face
                        ];
                        $this->jasseasemob->yy_hxSend_audio($username, explode(',', $groups),$dd['uri'].'/'.$dd['entities'][0]['uuid'], $file, $dd['size'], $dd['entities'][0]['share-secret'], 'chatgroups', $ext);
                    }
                    User::addPoints($uid, config('web.SENDUSERS_POINTS'));
                    return $this->result->responses([
                        'status' => 'success',
                        'status_code' => '',
                        'message' => '发送成功！'
                    ]);
                }
                else
                {
                    return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('发送失败！')->responseError();
                }
                break;
            case 'video':
                $dd = $this->uploadFile($file, $send_type);
                if ($dd == false)
                {
                    return $this->result->setStatusMsg('error')->setStatusCode(502)->setMessage('发送的文件大小请在1M以下！')->responseError();
                }
                $content = json_encode($dd);
                $save = [
                    'send_form_uid' => $uid,
                    'send_form_username' => $username,
                    'send_content' => $content,
                    'send_users' => $users,
                    'send_group_id' => $groups,
                    'send_time' => time()
                ];
                $statues = Send::create($save);
                if ($statues)
                {
                    if ($users != '')
                    {
                        $ext = [
                            'nickname' => $userInfo->nickname,
                            'user_face' => config('web.QINIU_URL').'/'.$userInfo->user_face
                        ];
                        //$from_user = "admin", $username, $filename, $thumb, $length, $secret, $file_length, $thumb_secret, $url,  $target_type = "users", $ext
                        $this->jasseasemob->yy_hxSend_video($username, explode(',', $users), $file, $dd['uri'].'/'.$dd['thumb_image_uuid'], $dd['duration'], $dd['entities'][0]['share-secret'], $dd['size'], $dd['thumb_image_share-secret'], 'https://a1.easemob.com/nicai/text/chatfiles/'.$dd['entities'][0]['uuid'], 'users', $ext);
                    }
                    else
                    {
                        $ext = [
                            'nickname' => $userInfo->nickname,
                            'user_face' => config('web.QINIU_URL').'/'.$userInfo->user_face
                        ];
                        $this->jasseasemob->yy_hxSend_video($username, explode(',', $groups), $file, $dd['uri'].'/'.$dd['thumb_image_uuid'], $dd['duration'], $dd['entities'][0]['share-secret'], $dd['size'], $dd['thumb_image_share-secret'], 'https://a1.easemob.com/nicai/text/chatfiles/'.$dd['entities'][0]['uuid'], 'chatgroups', $ext);
                    }
                    User::addPoints($uid, config('web.SENDUSERS_POINTS'));
                    return $this->result->responses([
                        'status' => 'success',
                        'status_code' => '',
                        'message' => '发送成功！'
                    ]);
                }
                else
                {
                    return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('发送失败！')->responseError();
                }
                break;
            default:
                # code...
                break;
        }

    }

    /**
     * 我今天剩余的次数
     * @param Request $request
     */
    public function surplusNum (Request $request)
    {
        $uid = $request->item['uid'];
        $now_time = Helpers::getToday(date('Y-m-d', time())); //今天的开始时间和结束时间

        /**
        * 判断用户每天发送的次数
        */
        $send = Send::where('send_time', '>=', $now_time['start'])->where('send_time', '<', $now_time['end'])->where('send_form_uid', $uid)->count();
        if ($send)
        {
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'surplusNum' => "".(config('web.TODAY_SEND_NUM')-$send)."",
                'message' => '发送成功！'
            ]);
        }
        else
        {
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'surplusNum' => "".(config('web.TODAY_SEND_NUM')-$send)."",
                'message' => '发送成功！'
            ]);
        }
    }

    /**
     * 上传文件
     * @param $file
     * @return mixed
     */
    public function uploadFile ($file, $type = 'img')
    {
        switch ($type) {
            case 'img':
                $url = config('web.QINIU_URL');
                $pic_name = $file;
                $images = $this->jasseasemob->uploadFile($url.'/'.$pic_name);
                if ($images == false)
                {
                    return false;
                }
                $qiniu = $this->jasseasemob->postCurl($url.'/'.$pic_name.'?avinfo');
                if (isset($qiniu['error']))
                {
                    return false;
                }
                $images['width'] = $qiniu['streams'][0]['width'];
                $images['height'] = $qiniu['streams'][0]['height'];
                return $images;
                break;
            case 'audio':
                $url = config('web.QINIU_URL');
                $pic_name = $file;
                $audio = $this->jasseasemob->uploadFile($url.'/'.$pic_name);
                if ($audio == false)
                {
                    return false;
                }
                $qiniu = $this->jasseasemob->postCurl($url.'/'.$pic_name.'?avinfo');
                if (isset($qiniu['error']))
                {
                    return false;
                }
                $audio['size'] = ($qiniu['format']['size']/1024);
                return $audio;
                break;
            case 'video':
                $url = config('web.QINIU_URL');
                $pic_name = $file;
                /*上传视频 - 开始*/
                $video = $this->jasseasemob->uploadFile($url.'/'.$pic_name);
                if ($video == false)
                {
                    return false;
                }
                $qiniu = $this->jasseasemob->postCurl($url.'/'.$pic_name.'?avinfo');
                $video['duration'] = $qiniu['format']['duration'];
                $video['size'] = ($qiniu['format']['size']/1024);
                if (isset($qiniu['error']))
                {
                    return false;
                }
                /*上传视频 - 结束*/

                /*上传视频缩略图 - 开始*/
                $thumb_video_pic_url = $url.'/'.$pic_name.'?vframe/jpg/offset/1/w/480/h/360';
                $video_images = $this->jasseasemob->uploadFile($thumb_video_pic_url);
                $video['thumb_image_uuid'] = $video_images['entities'][0]['uuid'];
                $video['thumb_image_share-secret'] = $video_images['entities'][0]['share-secret'];
                $video['thumb_image_uri'] = $video_images['uri'];
                /*上传视频缩略图 - 结束*/
                return $video;
                break;
            default:
                # code...
                break;
        }
    }
}
