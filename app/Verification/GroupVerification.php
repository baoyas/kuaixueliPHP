<?php

namespace App\Verification;
use App\Http\Controllers\Api\ResultController as Result;
use App\Model\Group;
use App\Model\User;
use Illuminate\Http\Request;
use App\Lib\JassEasemob;
use App\Transformer\GroupuserTransformer;
use App\Transformer\SearchgroupTransformer;
use Illuminate\Support\Facades\Config;

/**
 * Class GroupVerification
 *
 * @package \App\Verification
 */
class GroupVerification
{
    public function __construct ()
    {
        $this->result = new Result();
        $this->jasseasemob = new JassEasemob();
        $this->groupusertransformer = new GroupuserTransformer();
        $this->searchgrouptransformer = new SearchgroupTransformer();
    }

    /**
     * 群创建
     * @param Request $request
     */
    public function create (Request $request)
    {
        $uid = $request->item['uid'];
        $phone = $request->item['username'];
        $group_name = $request->get('group_name'); // 群组名称 此属性为必须的
        $group_desc = $request->get('group_desc'); // 群组描述 此属性为必须的
        $group_public = $request->get('group_public'); // 是否是公开群  此属性为必须的 true or false
        $group_maxusers = 2000; //  群组成员最大数（包括群主），值为数值类型，默认值200，最大值2000，此属性为可选的。
        $group_approval = $request->get('group_approval'); // 加入公开群是否需要批准 没有这个属性的话默认是true, 此属性为可选的
        $group_owner = $phone; // 群组的管理员 此属性为必须的
        $group_face = $request->get('group_face'); //群头像

        /**
         * 判断群组名称
         */
        if ($group_name == '')
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('群组名称不能为空！')->responseError();
        }
        /**
         * 判断群组描述
         */
        if ($group_desc == '')
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('群组描述不能为空！')->responseError();
        }
        /**
         * 判断是否为公开群
         */
        if ($group_public == '')
        {
            return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('群组是否公开不能为空！')->responseError();
        }
        /**
         * 判断群加入是否需要批准
         */
        if ($group_approval == '')
        {
            return $this->result->setStatusMsg('error')->setStatusCode(406)->setMessage('加入群是否需要批准不能为空！')->responseError();
        }
        /**
         * 判断群头像是否为空
         */
        if ($group_face == '')
        {
            return $this->result->setStatusMsg('error')->setStatusCode(407)->setMessage('群头像未设置！')->responseError();
        }
        $save = [
            'group_name' => $group_name,
            'group_desc' => $group_desc,
            'group_public' => $group_public,
            'group_maxusers' => $group_maxusers,
            'group_approval' => $group_approval,
            'group_owner' => $group_owner,
            'owner_uid' => $uid,
            'group_create_time' => time(),
            'group_face' => $group_face
        ];
        $statues = Group::create($save);
        if ($statues)
        {
            $group_public = ($group_public == 1) ? true: false;
            $group_approval = ($group_approval == 1) ? true: false;
            $option = [
                'groupname' => $group_name,
                'desc' => $group_desc,
                'maxusers' => $group_maxusers,
                'public' => $group_public,
                'approval' => $group_approval,
                'owner' => $group_owner
            ];
            $dd = $this->jasseasemob->createGroups($option);
            $group = \Qiniu\json_decode($dd);
            $group_id = $group->data->groupid;
            Group::where('id', $statues->id)->update(['group_id' => $group_id]);
            
            User::addPoints($uid, 5);
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'message' => '群创建成功！'
            ]);
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(408)->setMessage('创建群失败！')->responseError();
        }
    }

    /**
     * 我的群组 的信息
     * @param Request $request
     */
    public function groupInfo (Request $request)
    {
        $uid = $request->item['uid'];
        $group_id = $request->get('group_id');

        $group = Group::where('group_id', $group_id)->first();
        if ($group)
        {
            $groupInfo = [
                'bd_group_id' => "".$group->id."",
                'group_name' => $group->group_name,
                'group_desc' => $group->group_desc,
                'group_face' => config('web.QINIU_URL').'/'.$group->group_face,
                'hx_group_id' => $group->group_id
            ];
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'object' => [$groupInfo]
            ]);
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('没有查找到该群！')->responseError();
        }
    }

    /**
     * 群组修改昵称
     * @param Request $request
     */
    public function groupEditGroupname (Request $request)
    {
        $uid = $request->item['uid'];
        $group_id = $request->get('group_id'); //本地群组id
        $groupname = $request->get('groupname');
        $group = Group::where('id', $group_id)->first();
        if ($group)
        {
            /**
             * 判断群组是否是我创建的
             */
            if ($group->owner_uid == $uid)
            {
                $_groupname = str_replace(" ", '+', str_replace("/", "", $groupname));
                /**
                 * 判断群组昵称是否有重复
                 */
                if ($group->groupname == $_groupname)
                {
                    return $this->result->setStatusMsg('error')->setStatusCode(406)->setMessage('该群组昵称已被使用！')->responseError();
                }
                else
                {
                    $group->group_name = $_groupname;
                    $statues = $group->update();
                    if ($statues)
                    {
                        $options = [
                            'group_id' => $group->group_id,
                            'groupname' => $_groupname
                        ];
                        $this->jasseasemob->editGroupnickname($options);
                        return $this->result->responses([
                            'status' => 'success',
                            'status_code' => '',
                            'message' => '群组昵称修改成功！'
                        ]);
                    }
                    else
                    {
                        return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('群组昵称修改失败！')->responseError();
                    }
                }
            }
            else
            {
                return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('您没有权限修改群昵称！')->responseError();
            }
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('没有查找到该群！')->responseError();
        }
    }

    /**
     * 群组修改描述
     * @param Request $request
     */
    public function groupEditDescribe (Request $request)
    {
        $uid = $request->item['uid'];
        $group_id = $request->get('group_id'); //本地群组id
        $description = $request->get('description');
        $group = Group::where('id', $group_id)->first();
        if ($group)
        {
            /**
             * 判断群组是否是我创建的
             */
            if ($group->owner_uid == $uid)
            {
                $_description = str_replace(" ", '+', str_replace("/", "", $description));
                $group->group_desc = $_description;
                $statues = $group->update();
                if ($statues)
                {
                    $options = [
                        'group_id' => $group->group_id,
                        'description' => $_description
                    ];
                    $this->jasseasemob->editGroupnickname($options);
                    return $this->result->responses([
                        'status' => 'success',
                        'status_code' => '',
                        'message' => '群组描述修改成功！'
                    ]);
                }
                else
                {
                    return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('群组描述修改失败！')->responseError();
                }
            }
            else
            {
                return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('您没有权限修改群描述！')->responseError();
            }
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('没有查找到该群！')->responseError();
        }
    }

    /**
     * 群组修改头像
     * @param Request $request
     */
    public function groupEditGroupFace (Request $request)
    {
        $uid = $request->item['uid'];
        $group_id = $request->get('group_id'); //本地群组id
        $group_face = $request->get('group_face');
        $group = Group::where('id', $group_id)->first();
        if ($group)
        {
            /**
             * 判断群组是否是我创建的
             */
            if ($group->owner_uid == $uid)
            {
                $group->group_face = $group_face;
                $statues = $group->update();
                if ($statues)
                {
                    return $this->result->responses([
                        'status' => 'success',
                        'status_code' => '',
                        'message' => '群组头像修改成功！',
                        'group_face' => config('web.QINIU_URL').'/'.$group_face
                    ]);
                }
                else
                {
                    return $this->result->setStatusMsg('error')->setStatusCode(405)->setMessage('群组头像修改失败！')->responseError();
                }
            }
            else
            {
                return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('您没有权限修改群头像！')->responseError();
            }
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('没有查找到该群！')->responseError();
        }
    }

    /**
     * 群组全部成员
     * @param Request $request
     */
    public function members (Request $request)
    {
        $uid = $request->item['uid'];
        $am = User::where('id', $uid)->first();
        $group_id = $request->get('group_id');
        $groupInfo = Group::where('group_id', $group_id)->first();
        if ($groupInfo)
        {
            $member = $this->jasseasemob->groupsUser($group_id);
            $members = \Qiniu\json_decode($member, true);
            $members_id = [];
            $ower_id = [];
            foreach ($members['data'] as $k=>$v)
            {
                if (array_key_exists('member', $v))
                {
                    $members_id[] = $v['member'];
                }
                else
                {
                    $ower_id[] = $v['owner'];
                }
            }
            $last_members_id = array_merge($members_id, $ower_id);
            /**
             * 判断我在不在这个群里
             */
            if (!in_array($am->phone, $last_members_id))
            {
                return $this->result->setStatusMsg('error')->setStatusCode(402)->setMessage('您不在这个群中！')->responseError();
            }
            $user = User::whereIn('phone', $last_members_id)->select('id', 'nickname', 'user_face', 'phone')->get()->toArray();
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'object' => $this->groupusertransformer->transformController($user)
            ]);
        }
        else
        {
            return $this->result->setStatusMsg('error')->setStatusCode(403)->setMessage('没有查找到该群！')->responseError();
        }
    }

    /**
     * 搜索群
     * @param Request $request
     */
    public function search (Request $request)
    {
        $uid = $request->item['uid'];
        $am = User::where('id', $uid)->first();
        $group_name = $request->get('group_name');
        $group = Group::where('group_name', 'like', "%$group_name%")->orderBy('id', 'desc')->paginate(Config::get('web.api_page'))->toArray();
        if (empty($group['data']))
        {
            return $this->result->setStatusMsg('error')->setStatusCode(0)->setMessage('没有更多的数据了！')->responsePage();
        }
        else
        {
            foreach ($group['data'] as $k=>$v)
            {
                $group['data'][$k]['group_face'] = Config::get('web.QINIU_URL').'/'.$v['group_face'];
                $group_in_join = $this->joinGroup($am->phone, $v['group_id']);
                if ($group_in_join == 3)
                {
                    //判断 该群在环信中是否 解散 ，如果解散 删除本地群组 并把 该群在 数组中删除
                    Group::where('group_id', $v['group_id'])->delete();
                    unset($group['data'][$k]);
                }
                else
                {
                    $group['data'][$k]['joinGroup'] = $group_in_join;
                }
            }
            
            return $this->result->responses([
                'status' => 'success',
                'status_code' => '',
                'object' => $this->searchgrouptransformer->transformController(array_values($group['data']))
            ], $group);
        }
    }

    /**
     * 查看我是否在本群
     * @param $user_phone  我的手机号
     * @param $group_id  环信群id
     */
    public function joinGroup ($user_phone, $group_id)
    {
        $member = $this->jasseasemob->groupsUser($group_id);
        $members = \Qiniu\json_decode($member, true);
        if (array_key_exists('error', $members))
        {
            return 3;
        }
        $members_id = [];
        $ower_id = [];
        foreach ($members['data'] as $k=>$v)
        {
            if (array_key_exists('member', $v))
            {
                $members_id[] = $v['member'];
            }
            else
            {
                $ower_id[] = $v['owner'];
            }
        }
        $last_members_id = array_merge($members_id, $ower_id);

        if(in_array($user_phone, $last_members_id))
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
}
