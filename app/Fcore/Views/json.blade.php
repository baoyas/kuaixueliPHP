
@foreach($grid->columns() as $column)
    <th>{{$column->getLabel()}}</th>
@endforeach


@foreach($grid->rows() as $row)
<tr {!! $row->getHtmlAttributes() !!}>
    @foreach($grid->columnNames as $name)
        <td>{!! $row->column($name) !!}</td>
    @endforeach
</tr>
@endforeach

{{-- 此注释将不会出{!! $grid->paginator() !!}现在渲染后的 HTML --}}

