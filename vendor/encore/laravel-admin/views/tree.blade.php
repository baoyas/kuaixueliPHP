<style type="text/css">
.dd{position:relative;display:block;margin:0;padding:0;max-width:600px;list-style:none;line-height:20px;}
.dd-list{position:relative;display:block;margin:0;padding:0;list-style:none;}
.dd-list .dd-list{padding-left:30px;}
.dd-collapsed .dd-list{display:none;}
.dd-empty,.dd-item,.dd-placeholder{position:relative;display:block;margin:0;padding:0;min-height:20px;line-height:20px;}
.dd-handle,.dd2-content{display:block;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;margin:5px 0;padding:8px 12px;min-height:38px;border:1px solid #dae2ea;background:#f8faff;text-decoration:none;}
.dd-handle:hover,.dd2-content:hover{border-color:#dce2e8;background:#f4f6f7;color:#438eb9;}
.dd-handle[class*=btn-],.dd2-content[class*=btn-]{padding:9px 12px;border:0;color:#FFF;}
.dd-handle[class*=btn-]:hover,.dd2-content[class*=btn-]:hover{color:#FFF;opacity:.85;}
.dd2-handle+.dd2-content,.dd2-handle+.dd2-content[class*=btn-]{padding-left:44px;}
.dd-handle[class*=btn-]:hover,.dd2-content[class*=btn-] .dd2-handle[class*=btn-]:hover+.dd2-content[class*=btn-]{color:#FFF;}
.dd-item>button:hover~.dd-handle,.dd-item>button:hover~.dd2-content{border-color:#dce2e8;background:#f4f6f7;color:#438eb9;}
.dd-item>button:hover~.dd-handle[class*=btn-],.dd-item>button:hover~.dd2-content[class*=btn-]{color:#FFF;opacity:.85;}
.dd2-handle:hover~.dd2-content{border-color:#dce2e8;background:#f4f6f7;color:#438eb9;}
.dd2-handle:hover~.dd2-content[class*=btn-]{color:#FFF;opacity:.85;}
.dd2-item.dd-item>button{margin-left:34px;}
.dd-item>button{position:relative;top:4px;left:1px;z-index:1;float:left;display:block;overflow:hidden;margin:5px 1px 5px 5px;padding:0;width:25px;height:20px;border:0;background:0 0;color:#707070;text-align:center;text-indent:100%;white-space:nowrap;font-weight:700;font-size:12px;line-height:1;cursor:pointer;}
.dd-item>button:before{position:absolute;display:block;width:100%;content:'\f067';text-align:center;text-indent:0;font-weight:400;font-size:14px;font-family:FontAwesome;}
.dd-item>button[data-action=collapse]:before{content:'\f068';}
.dd-item>button:hover{color:#707070;}
.dd-item.dd-colored>button,.dd-item.dd-colored>button:hover{color:#EEE;}
.dd-empty,.dd-placeholder{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;margin:5px 0;padding:0;min-height:30px;border:2px dashed #bed2db;background:#f0f9ff;}
.dd-empty{border-color:#AAA;border-style:solid;background-color:#e5e5e5;}
.dd-dragel{position:absolute;z-index:999;opacity:.8;pointer-events:none;}
.dd-dragel>li>.dd-handle{position:relative;border-color:#d6e1ea;border-left:2px solid #777;background:#f1f5fa;color:#4b92be;}
.dd-dragel>li>.dd-handle[class*=btn-]{color:#FFF;}
.dd-dragel>.dd-item>.dd-handle{margin-top:0;}
.dd-list>li[class*=item-]{padding:0;border-width:0;}
.dd-list>li[class*=item-]>.dd-handle{border-left:2px solid;border-left-color:inherit;}
.dd-list>li>.dd-handle .sticker{position:absolute;top:0;right:0;}
.dd-dragel>li>.dd2-handle,.dd2-handle{position:absolute;top:0;left:0;z-index:1;overflow:hidden;margin:0;padding:0!important;width:36px;height:38px;border:1px solid #dee4ea;border-width:1px 1px 0 0;background:#ebedf2;text-align:center;line-height:38px;cursor:pointer;}
.dd-dragel>li>.dd2-handle,.dd2-handle:hover{background:#e3e8ed;}
.dd2-content[class*=btn-]{text-shadow:none!important;}
.dd2-handle[class*=btn-]{border-right:1px solid #EEE;background:rgba(0,0,0,.1)!important;text-shadow:none!important;}
.dd2-handle[class*=btn-]:hover{background:rgba(0,0,0,.08)!important;}
.dd-dragel .dd2-handle[class*=btn-]{border-color:transparent;border-right-color:#EEE;}
.dd2-handle.btn-yellow{border-right:1px solid #FFF;background:rgba(0,0,0,.05)!important;text-shadow:none!important;}
.dd2-handle.btn-yellow:hover{background:rgba(0,0,0,.08)!important;}
.dd-dragel .dd2-handle.btn-yellow{border-color:transparent;border-right-color:#FFF;}
.dd-item>.dd2-handle .drag-icon{display:none;}
.dd-dragel>.dd-item>.dd2-handle .drag-icon{display:inline;}
.dd-dragel>.dd-item>.dd2-handle .normal-icon{display:none;}
.dropzone{border:1px solid rgba(0,0,0,.06);border-radius:0;}
.dropzone .dz-default.dz-message{left:0;margin-left:auto;width:100%;background-image:none;text-align:center;font-size:24px;line-height:32px;}
.dropzone .dz-default.dz-message span{display:inline;color:#555;}
.dropzone .dz-default.dz-message span .upload-icon{margin-top:8px;opacity:.7;cursor:pointer;filter:alpha(opacity=70);}
.dropzone .dz-default.dz-message span .upload-icon:hover{opacity:1;filter:alpha(opacity=100);}
.dropzone .dz-preview .dz-error-mark,.dropzone .dz-preview .dz-success-mark,.dropzone-previews .dz-preview .dz-error-mark,.dropzone-previews .dz-preview .dz-success-mark{border-radius:100%;background-color:rgba(255,255,255,.8);background-image:none;text-align:center;line-height:35px;}
.dropzone .dz-preview .dz-error-mark:before,.dropzone-previews .dz-preview .dz-error-mark:before{color:#db6262;content:"\f00d";font-size:30px;font-family:FontAwesome;}
.dropzone .dz-preview .dz-success-mark:before,.dropzone-previews .dz-preview .dz-success-mark:before{color:#6da552;content:"\f00c";font-size:30px;font-family:FontAwesome;}
.dropzone a.dz-remove,.dropzone-previews a.dz-remove{border:0;border-radius:0;background:#d15b47;color:#FFF;cursor:pointer;}
.dropzone a.dz-remove:hover,.dropzone-previews a.dz-remove:hover{background:#b74635;color:#FFF;}
.dropzone .progress,.dropzone-previews .progress{margin-bottom:0;}
.dropzone .dz-preview.dz-error .progress,.dropzone .dz-preview.dz-success .progress,.dropzone-previews .dz-preview.dz-error .progress,.dropzone-previews .dz-preview.dz-success .progress{display:none;}
.icon-animated-bell{display:inline-block;-webkit-transform-origin:50% 0;-moz-transform-origin:50% 0;-o-transform-origin:50% 0;transform-origin:50% 0;-moz-animation:ringing 2s 5 ease 1s;-webkit-animation:ringing 2s 5 ease 1s;-o-animation:ringing 2s 5 ease 1s;-ms-animation:ringing 2s 5 ease 1s;animation:ringing 2s 5 ease 1s;-ms-transform-origin:50% 0;}
.dd-handle .pull-right{float:right!important;}
.dd-handle .pull-right:before{content:"";}
</style>
<div class="box">

    <div class="box-header">

        <div class="btn-group">
            <a class="btn btn-primary btn-sm {{ $id }}-tree-tools" data-action="expand">
                <i class="fa fa-plus-square-o"></i>&nbsp;{{ trans('admin::lang.expand') }}
            </a>
            <a class="btn btn-primary btn-sm {{ $id }}-tree-tools" data-action="collapse">
                <i class="fa fa-minus-square-o"></i>&nbsp;{{ trans('admin::lang.collapse') }}
            </a>
        </div>

        <div class="btn-group">
            <a class="btn btn-info btn-sm  {{ $id }}-save"><i class="fa fa-save"></i>&nbsp;{{ trans('admin::lang.save') }}</a>
        </div>

        <div class="btn-group">
            <a class="btn btn-warning btn-sm {{ $id }}-refresh"><i class="fa fa-refresh"></i>&nbsp;{{ trans('admin::lang.refresh') }}</a>
        </div>

        @if($useCreate)
        <div class="btn-group pull-right">
            <a class="btn btn-success btn-sm" href="/{{ $path }}/create"><i class="fa fa-save"></i>&nbsp;{{ trans('admin::lang.new') }}</a>
        </div>
        @endif

    </div>
    <!-- /.box-header -->
    <div class="box-body no-padding">
        <div class="dd" id="{{ $id }}">
            <ol class="dd-list">
                @each($branchView, $items, 'branch')
            </ol>
        </div>
    </div>
    <!-- /.box-body -->
</div>