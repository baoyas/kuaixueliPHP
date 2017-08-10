<?php

namespace App\Adminlte\Controllers;

use App\Helpers\Helpers;
use App\Model\Bankroll;
use App\Model\Sell;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Lib\JassEasemob;



use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\MessageBag;


use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Chart\Bar;
use Encore\Admin\Widgets\Chart\Doughnut;
use Encore\Admin\Widgets\Chart\Line;
use Encore\Admin\Widgets\Chart\Pie;
use Encore\Admin\Widgets\Chart\PolarArea;
use Encore\Admin\Widgets\Chart\Radar;
use Encore\Admin\Widgets\Collapse;
use Encore\Admin\Widgets\InfoBox;
use Encore\Admin\Widgets\Tab;
use Encore\Admin\Widgets\Table;
use App\Adminlte\Extensions\Tools\ReleasePost;

class UserController extends Controller
{
    use ModelForm;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
        
            $content->header('用户管理');
            $content->description('列表');
        
            $content->body($this->grid());
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.user.add');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::where('id', $id)->first();
        if ($user->is_del) {
            return redirect('adminlte/user');
        }
        //Admin::css(['/style/admin/css/style.css','/style/admin/css/framework.css']);
        Admin::js('style/admin/layer/layer.js');
        Admin::js('js/adminlte/user.js');
        return Admin::content(function (Content $content) use($id) {
            $content->header('用户详情');
            $content->description('...');
            $user = User::where('id', $id)->first();
            $sellGrid = Admin::grid(Sell::class, function (Grid $grid) use($id) {
                $grid->model()->where(['sell_uid'=>$id, 'is_del'=>0]);
                $grid->column('id', 'ID')->sortable();
                $grid->column('is_sell', '类别')->display(function(){
                    if($this->is_sell == 1 && $this->is_circle == 0) {
                        return '出售';
                    } elseif($this->is_circle == 1) {
                        return '朋友圈';
                    } else {
                        return '购买';
                    }
                });
                $grid->column('sell_title', '标题');
                $grid->column('sell_time', '发布时间')->display(function () {
                    return date('Y-m-d H:i:s', $this->sell_time);
                })->sortable();
                $grid->actions(function (Grid\Displayers\Actions $actions) {
                    $actions->disableDelete();
                    $actions->disableEdit();
                    if($this->row->is_sell == 1 && $this->row->is_circle == 0) {
                        $actions->append('<a><i class="fa fa-eye"></i></a> ');
                    } elseif($this->row->is_circle == 1) {
                        $actions->append('<a class="grid-row-delete" data-id="'.$this->getKey().'"><i class="fa fa-trash"></i></a> ');
                    } else {
                        $actions->append('<a><i class="fa fa-eye"></i></a> ');
                    }
                });
                $grid->tools(function (Grid\Tools $tools) {
                    $tools->disableRefreshButton();
                    $tools->batch(function (Grid\Tools\BatchActions $actions) {
                        $actions->disableDelete();
                        $actions->add('发布文章', new ReleasePost(1));
                        $actions->add('文章下线', new ReleasePost(0));
                    });
                });
                $grid->disableRowSelector();
                $grid->disableExport();
                $grid->disableCreation();
                $grid->disableFilter();
            });
            $sellGrid->title = 'TA的发布';
            $content->row(function ($row) use($user, $sellGrid){
                $row->column(3, new Box('基本信息','<img style="width:120px;height:120px;display:block;border-radius:50%;margin:auto;" src="'.config('web.QINIU_URL').'/'.$user->user_face.'" />
				<p>ID:'.$user->id.'</p>
				<p class="mt10"><b>'.$user->nickname.'</b></p>
				<p>'.$user->phone.'</p>
				<p class="identity" style="width:100px;margin:6px auto;">
					<span class="label label-success">普通用户</span>
				</p>
				<p class="pb10 bline mb10">'.$user->autograph.'</p>
				<p>注册时间：'.date('Y-m-d H:i:s', $user->user_reg_time).'</p>
				<p>地区：'.$user->area.'</p>
				<p>地址：'.$user->address.'</p>
				<button class="btn btn-info pull-left">修改密码</button>
				<button class="btn btn-info pull-left grid-row-statues" data-id="'.$user->id.'"  data-statues="'.$user->statues.'" style="margin-left:4px;">'.($user->statues==0?'禁用':'开启').'</button>
				<button class="btn btn-info pull-left grid-row-delete" data-id="'.$user->id.'" style="margin-left:4px;">删除</button>'));
                $row->column(9, $sellGrid);

            });
        });
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = User::find($id);
        return view('admin.user.edit', compact('data'));
    }
    

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        Admin::script('$("header").append("<meta name=csrf-token content='.csrf_token().' />")');
        Admin::js('style/admin/layer/layer.js');
        Admin::js('js/adminlte/user.js');
        return Admin::grid(User::class, function (Grid $grid) {
            $grid->column('id', '用户ID')->sortable();
            $grid->column('mobile', '手机号')->prependIcon('phone');
            $grid->column('realname', '姓名');
            $grid->column('email', '邮箱')->sortable();
            $grid->column('created_at', '注册时间')->sortable()->prependIcon('clock-o');

            $grid->filter(function ($filter) {
                $filter->is('mobile', '手机号');
                $filter->like('realname', '姓名');
                //$filter->useModal();
            });
            $grid->disableActions();
            $grid->disableBatchDeletion();
            $grid->disableExport();
            $grid->disableCreation();
        });
    }
    
    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return User::form(function (Form $form) {
            $form->text('statues', '状态');//->rules('required');
            $form->saving(function (Form $form) {
                if (property_exists($form, 'statues')) {
                    $form->statues = $form->statues;
                }
            });
        });
    }
}
