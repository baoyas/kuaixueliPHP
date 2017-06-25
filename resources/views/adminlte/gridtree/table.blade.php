
<div class="box">
    <div class="box-header">

        <h3 class="box-title">{!! $grid->renderTitle() !!}</h3>

        <div class="pull-right">
            {!! $grid->renderFilter() !!}
            {!! $grid->renderExportButton() !!}
            {!! $grid->renderCreateButton() !!}
        </div>

        <span>
            {!! $grid->renderHeaderTools() !!}
        </span>

    </div>
    <!-- /.box-header -->
    <div class="box-body table-responsive no-padding">
        <!-- tree -->
        <!-- /tree -->
        <table class="table table-hover">
            <tr>
                @foreach($grid->columns() as $column)
                <th>{{$column->getLabel()}}{!! $column->sorter() !!}</th>
                @endforeach
            </tr>

            <tbody data-tk="data-row" data-pid="{{ $pid }}">
            @foreach($grid->rows() as $row)
            <tr {!! $row->getHtmlAttributes() !!} id="grid-tree-row-{{ $row->id() }}" data-pidstr="{{ $pidstr.'-'.$row->id }}">
                @foreach($grid->columnNames as $index=>$name)
                @if($index == 0 && $level<=1)
                <td class="dd-item-tk">
                <i class="fa fa-plus" data-loading-text="<i class='fa fa-spinner fa-spin'></i>" data-action="expand" data-id={{ $row->id() }} data-level="{{ $level }}" style="cursor:pointer;margin-left:{{ $level*20}}px;" onclick="loadChildren(this)"></i>
                {!! $row->column($name) !!}
                </td>
                @elseif($index == 0)
                <td><i style="cursor:pointer;margin-left:{{ $level*20 }}px;"></i>{!! $row->column($name) !!}</td>
                @else
                <td>{!! $row->column($name) !!}</td>
                @endif
                @endforeach
            </tr>
            @endforeach
            </tbody>
            
        </table>
    </div>
    <div class="box-footer clearfix">
        {!! $grid->paginator() !!}
    </div>
    <!-- /.box-body -->
</div>
<script>
function loadChildren(obj) {
    var $obj = $(obj);
    var $tr = $obj.closest('tr');
    var id = $obj.attr("data-id");
    var level = $obj.attr("data-level");
    var pidstr = $tr.attr('data-pidstr');
    if($obj.attr('data-action')=='collapse') {
        //$('tbody[data-tk=data-row]>tr[data-pidstr^='+pidstr+']').not('[data-pidstr$='+pidstr+']').hide();
    	$('tbody[data-tk=data-row][data-pid='+id+']').hide();
        $obj.attr('data-action', 'expand');
        $obj.attr('class', 'fa fa-plus');
    } else if($obj.attr('data-action')=='expand') {
        if($('tbody[data-tk=data-row][data-pid='+id+']').length>0) {
        	$('tbody[data-tk=data-row][data-pid='+id+']').show();
        	$obj.attr('data-action', 'collapse');
            $obj.attr('class', 'fa fa-minus');
        } else {
            $obj.button('loading');
            $obj.attr('disabled', true);
            $.ajax({
                url:document.URL,
                data:{_token:LA.token, pid:id, level:parseInt(level)+1, pidstr:pidstr},
                dataType:'text',
                success:function(data){
                    $html = $(data).find('tbody[data-tk=data-row]').html();//prop('outerHTML');
                    $obj.closest('tr').after('<x-tree>'+$html+'</x-tree>');
                    $obj.attr('data-action', 'collapse');
                    $obj.attr('class', 'fa fa-minus');
                    $obj.attr('disabled', false);
                    $obj.button('reset');
                }
            });
        }
    }
}
$(document).ready(function(){
    $('td[class=dd-item]');
});
</script>