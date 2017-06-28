
{{-- 此注释将不会出{!! $grid->paginator() !!}现在渲染后的 HTML --}}
<?php
foreach($grid->columns() as $k=>$column) {
    $keys[$k] = $column->getLabel();
}

$rowData = [];
foreach($grid->rows() as $rIndex=>$row) {
    foreach($grid->columnNames as $k=>$name) {
        $rowData[$rIndex][$keys[$k]] = $row->column($name);
    }
}
if($format=='json') {
    echo json_encode($rowData, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
} elseif($format=='array') {
    echo json_encode($rowData, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
} elseif($format=='object') {
    echo json_encode($rowData[0], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
}


