<div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6">

        @include('admin::form.error')

        <script type="text/plain" id="{{$id}}"></script>
        <script>
            $(document).ready(function(){
                var edata = {!! json_encode(array($id=>$value)) !!}
                var ue = UE.getEditor("{{$id}}");
                ue.ready(function() {
                    UE.getEditor("{{$id}}").execCommand('insertHtml', edata.{{$id}});
                });
            });
        </script>
        @include('admin::form.help-block')

    </div>
</div>