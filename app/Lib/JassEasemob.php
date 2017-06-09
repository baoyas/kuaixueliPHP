<?php

namespace App\Lib;

use App\Lib\Easemob;
/**
 * Class JassEasemob
 *
 * @package \App\Lib
 */
class JassEasemob
{
    public function __construct ()
    {
        $options = [
            'client_id' => config('web.Client_Id'),
            'client_secret' => config('web.Client_Secret'),
            'org_name' => config('web.ORG_NAME'),
            'app_name' => config('web.APP_NAME')
        ];
        $this->easemob = new Easemob($options);
    }

    /**
     * 开放注册模式
     *
     * @param $options['username'] 用户名
     * @param $options['password'] 密码
     */
    public function openRegister($options) {
        return $this->easemob->openRegister($options);
    }

    /**
     * 授权注册模式 || 批量注册
     *
     * @param $options['username'] 用户名
     * @param $options['password'] 密码
     *        	批量注册传二维数组
     */
    public function accreditRegister($options) {
        return $this->easemob->accreditRegister($options);
    }

    /**
     * 获取指定用户详情
     *
     * @param $username 用户名
     */
    public function userDetails($username) {
        return $this->easemob->userDetails($username);
    }

    /**
     * 重置用户密码
     *
     * @param $options['username'] 用户名
     * @param $options['password'] 密码
     * @param $options['newpassword'] 新密码
     */
    public function editPassword($options) {
        return $this->easemob->editPassword($options);
    }

    /*
     * 用户修改昵称
     * @param $options['username'] 用户名
     * @param $options['nickname'] 昵称
     */
    public function editNickname ($options)
    {
        return $this->easemob->editNickname($options);
    }

    /**
     * 删除用户
     *
     * @param $username 用户名
     */
    public function deleteUser($username) {
        return $this->easemob->deleteUser($username);
    }

    /**
     * 批量删除用户
     * 描述：删除某个app下指定数量的环信账号。上述url可一次删除300个用户,数值可以修改 建议这个数值在100-500之间，不要过大
     *
     * @param $limit="300" 默认为300条
     * @param $ql 删除条件
     *        	如ql=order+by+created+desc 按照创建时间来排序(降序)
     */
    public function batchDeleteUser($limit = "300", $ql = '') {
        return $this->easemob->batchDeleteUser($limit, $ql);
    }

    /**
     * 给一个用户添加一个好友
     *
     * @param
     *        	$owner_username
     * @param
     *        	$friend_username
     */
    public function addFriend($owner_username, $friend_username) {
        return $this->easemob->addFriend($owner_username, $friend_username);
    }

    /**
     * 删除好友
     *
     * @param
     *        	$owner_username
     * @param
     *        	$friend_username
     */
    public function deleteFriend($owner_username, $friend_username) {
        return $this->easemob->deleteFriend($owner_username, $friend_username);
    }

    /**
     * 查看用户的好友
     *
     * @param
     *        	$owner_username
     */
    public function showFriend($owner_username) {
        $phone = $this->easemob->showFriend($owner_username);
        $user = \Qiniu\json_decode($phone);
    
        $_user = [
            'phone' => $user->data,
            'count' => $user->count
        ];
        return $_user;
    }

    // +----------------------------------------------------------------------
    // | 聊天相关的方法
    // +----------------------------------------------------------------------
    /**
     * 查看用户是否在线
     *
     * @param
     *        	$username
     */
    public function isOnline($username) {
        return $this->easemob->isOnline($username);
    }
    /**
     * 发送消息
     *
     * @param string $from_user
     *        	发送方用户名
     * @param array $username
     *        	array('1','2')
     * @param string $target_type
     *        	默认为：users 描述：给一个或者多个用户(users)或者群组发送消息(chatgroups)
     * @param string $content
     * @param array $ext
     *        	自定义参数
     */
    public function yy_hxSend($from_user = "admin", $username, $content, $target_type = "users", $ext) {
        return $this->easemob->yy_hxSend($from_user, $username, $content, $target_type, $ext);
    }

    /**
     * 群发 - 图片
     * @param string $from_user
     * @param $username
     * @param $url
     * @param $content
     * @param $secret
     * @param $width
     * @param $height
     * @param string $target_type
     * @param $ext
     * @return mixed
     */
    public function yy_hxSend_img($from_user = "admin", $username, $url, $content, $secret, $width, $height, $target_type = "users", $ext)
    {
        return $this->easemob->yy_hxSend_img($from_user, $username, $url, $content, $secret, $width, $height, $target_type, $ext);
    }

    /**
     * 群发 - 声音
     * @param string $from_user  谁发送的
     * @param $username  都谁接受
     * @param $url 成功上传文件返回的UUID
     * @param $filename 指定一个文件名
     * @param $length 大小
     * @param $secret 成功上传文件后返回的secret
     * @param string $target_type
     * @param $ext
     * @return mixed
     */
    public function yy_hxSend_audio($from_user = "admin", $username, $url, $filename, $length,  $secret, $target_type = "users", $ext) {
        return $this->easemob->yy_hxSend_audio($from_user, $username, $url, $filename, $length,  $secret, $target_type, $ext);
    }

    /**
     * 群发 - 视频
     * @param $result 视频上传返回
     * @param string $from 谁发送的
     * @param string $target_type 用户
     * @param $target 谁接受
     * @param $filename 文件名称
     * @param $length 长度
     * @param $thumb
     * @param $thumb_secret
     * @param $ext
     * @return mixed
     */
    public function yy_hxSend_video($from_user = "admin", $username, $filename, $thumb, $length, $secret, $file_length, $thumb_secret, $url,  $target_type = "users", $ext)
    {
        return $this->easemob->yy_hxSend_video($from_user, $username, $filename, $thumb, $length, $secret, $file_length, $thumb_secret, $url,  $target_type, $ext);
    }
    /**
     * 发送透传消息
     *
     * @param string $from_user
     *        	发送方用户名
     * @param array $username
     *        	array('1','2')
     * @param string $target_type
     *        	默认为：users 描述：给一个或者多个用户(users)或者群组发送消息(chatgroups)
     * @param string $content
     * @param array $ext
     *        	自定义参数
     */
    public function cmd_hxSend($from_user = "admin", $username, $content, $target_type = "users", $ext) {
        return $this->easemob->cmd_hxSend($from_user, $username, $content, $target_type, $ext);
    }
    /**
     * 获取app中所有的群组
     */
    public function chatGroups() {
        return $this->easemob->chatGroups();
    }
    /**
     * 创建群组
     *
     * @param $option['groupname'] //群组名称,
     *        	此属性为必须的
     * @param $option['desc'] //群组描述,
     *        	此属性为必须的
     * @param $option['public'] //是否是公开群,
     *        	此属性为必须的 true or false
     * @param $option['approval'] //加入公开群是否需要批准,
     *        	没有这个属性的话默认是true, 此属性为可选的
     * @param $option['owner'] //群组的管理员,
     *        	此属性为必须的
     * @param $option['members'] //群组成员,此属性为可选的
     */
    public function createGroups($option) {
        return $this->easemob->createGroups($option);
    }
    /**
     * 获取群组详情
     *
     * @param
     *        	$group_id
     */
    public function chatGroupsDetails($group_id) {
        return $this->easemob->chatGroupsDetails($group_id);
    }

    /**
     * 群组修改昵称和描述
     * @param $options
     * @return mixed
     */
    public function editGroupnickname ($options)
    {
        return $this->easemob->editGroupnickname($options);
    }
    /**
     * 删除群组
     *
     * @param
     *        	$group_id
     */
    public function deleteGroups($group_id) {
        return $this->easemob->deleteGroups($group_id);
    }
    /**
     * 获取群组成员
     *
     * @param
     *        	$group_id
     */
    public function groupsUser($group_id) {
        return $this->easemob->groupsUser($group_id);
    }
    /**
     * 群组添加成员
     *
     * @param
     *        	$group_id
     * @param
     *        	$username
     */
    public function addGroupsUser($group_id, $username) {
        return $this->easemob->addGroupsUser($group_id, $username);
    }
    /**
     * 群组删除成员
     *
     * @param
     *        	$group_id
     * @param
     *        	$username
     */
    public function delGroupsUser($group_id, $username) {
        return $this->easemob->delGroupsUser($group_id, $username);
    }
    /**
     * 聊天消息记录
     *
     * @param $ql 查询条件如：$ql
     *        	= "select+*+where+from='" . $uid . "'+or+to='". $uid ."'+order+by+timestamp+desc&limit=" . $limit . $cursor;
     *        	默认为order by timestamp desc
     * @param $cursor 分页参数
     *        	默认为空
     * @param $limit 条数
     *        	默认20
     */
    public function chatRecord($ql = '', $cursor = '', $limit = 20) {
        return $this->easemob->chatRecord($ql, $cursor, $limit);
    }

    /**
     * 环信上传
     * @param $filePath
     * @return mixed
     */
    public function uploadFile ($filePath)
    {
        return $this->easemob->uploadFile($filePath);
    }

    public function postCurl($url, $post = '', $cookie = '', $returnCookie = 0) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
        if($post) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
        }
        if($cookie) {
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }
        curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return curl_error($curl);
        }
        curl_close($curl);
        if($returnCookie){
            list($header, $body) = explode("\r\n\r\n", $data, 2);
            preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
            $info['cookie']  = substr($matches[1][0], 1);
            $info['content'] = $body;
            return $info;
        }else{
            return \Qiniu\json_decode($data, true);
        }
    }
}
