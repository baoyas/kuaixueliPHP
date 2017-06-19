<?php

namespace App\Adminlte\Extensions\Form;

use Encore\Admin\Form\Field;

class UEditor extends Field
{
    public static $js = [
        "/laravel-u-editor/ueditor.config.js",
        "/laravel-u-editor/ueditor.all.min.js",
        "/laravel-u-editor/lang/zh-cn/zh-cn.js"
    ];

    protected $view = 'admin.ueditor';

    public function render()
    {
        //$this->script = "UE.getEditor('.{$this->getElementId()}')";
        $this->scriptsss = <<<EOT
//$('[name={$this->id}]').attr("id", '{$this->id}');
UE.getEditor("{$this->id}");
EOT;
        return parent::render();
    }
}