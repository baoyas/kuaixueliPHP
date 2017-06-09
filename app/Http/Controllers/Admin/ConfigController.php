<?php

namespace App\Http\Controllers\Admin;

use App\Model\Config;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ConfigController extends Controller
{
    //
    public function index ()
    {
        $data = Config::orderBy('conf_order', 'asc')->get();

        foreach($data as $k=>$v)
        {
            switch($v->field_type)
            {
                case 'input':
                    $data[$k]->_html = '<input type="text" class="input-text" name="conf_content[]" value="'.$v->conf_content.'">';
                    break;
                case 'textarea':
                    $data[$k]->_html = '<textarea type="text" class="textarea" name="conf_content[]">'.$v->conf_content.'</textarea>';
                    break;
                case 'checkbox':
                    $arr = explode(',', $v->field_value);
                    $str = '';
                    foreach($arr as $m=>$n)
                    {
                        $r = explode('|', $n);
                        $c = $v->conf_content==$r[0] ? ' checked ' :'';
                        $str .= '<input type="checkbox" name="conf_content[]"'.$c.' value="'.$r[0].'">' . $r[1] . '　';
                    }
                    $data[$k]->_html = $str;
                    break;
            }
        }
        return view('admin.config.index')->with('data', $data);
    }

    //网站配置项添加
    public function create()
    {
        return view('admin.config.add');
    }

    //POST  | admin/config  配置项提交处理
    public function store()
    {
        $input = Input::except('_token');
        $rules = [
            'conf_title' => 'required',
            'conf_name' => 'required',
        ];
        $message = [
            'conf_title.required' => '配置项标题不能为空！',
            'conf_name.required' => '配置项名称不能为空！'
        ];
        $validator = Validator::make($input, $rules, $message);
        if ($validator->passes())
        {
            $re = Config::create($input);
            if ($re)
            {
                return redirect('admin/config');
            }
            else
            {
                return back()->with('errors', '添加失败！');
            }
        }
        else
        {
            return back()->withErrors($validator);
        }
    }

    /**
     *GET|HEAD    | admin/config/{category}/edit  导航连接
     */
    public function edit ($conf_id)
    {
        $field = Config::find($conf_id);
        return view('admin.config.edit', compact('field'));
    }

    // PUT|PATCH  | admin/config/{category}  更新导航
    public function update ($conf_id)
    {
        $input = Input::except('_token', '_method');
        $rules = [
            'conf_title' => 'required',
            'conf_name' => 'required',
        ];
        $message = [
            'conf_title.required' => '配置项标题不能为空！',
            'conf_name.required' => '配置项名称不能为空！'
        ];
        $validator = Validator::make($input, $rules, $message);
        if ($validator->passes())
        {
            $re = Config::where('conf_id', $conf_id)->update($input);
            if ($re)
            {
                $this->putFile();
                return redirect('admin/config');
            }
            else
            {
                return back()->with('errors', '更新失败，请查看是否填写正确！');
            }
        }
        else
        {
            return back()->withErrors($validator);
        }
    }

    /**
     * 更新配置参数
     */
    public function changecontent ()
    {
        $input = Input::all();
        foreach($input['conf_id'] as $k=>$v)
        {
            Config::where('conf_id', $v)->update(['conf_content' => $input['conf_content'][$k]]);
        }
        $this->putFile();//配置项写入文件
        return back()->with('errors', '配置项更新成功！');
    }

    // DELETE   | admin/config/{category}  删除单个配置项
    public function destroy ($conf_id)
    {
        $info = Config::where('conf_id', $conf_id)->first();
        if ($info->is_system)
        {
            $data = [
                'status' => 1,
                'msg' => '系统定义配置不可删除！'
            ];
        }
        else
        {
            $re = Config::where('conf_id', $conf_id)->delete();
            if ($re)
            {
                $this->putFile();
                $data = [
                    'status' => 0,
                    'msg' => '删除成功！'
                ];
            }
            else
            {
                $data = [
                    'status' => 1,
                    'msg' => '删除失败，请稍后重试！'
                ];
            }
        }
        return $data;
    }

    /**
     * 配置项排序
     */
    public function changeorder ()
    {
        $input = Input::all();
        $cate = Config::find($input['conf_id']);
        $cate->conf_order = $input['conf_order'];
        $re = $cate->update();
        if ($re)
        {
            $data = [
                'status' => 0,
                'msg' => '排序更新成功！'
            ];
        }
        else
        {
            $data = [
                'status' => 1,
                'msg' => '排序更新失败，请稍后重试！'
            ];
        }
        return $data;
    }

    /**
     * 配置项写入文件
     */
    public function putFile ()
    {
        $data = Config::pluck( 'conf_content', 'conf_name')->all();
        $path = base_path() . '/config/web.php';
        $str = '<?php return ' . var_export($data, true) . ';';
        file_put_contents($path, $str);
    }
}
