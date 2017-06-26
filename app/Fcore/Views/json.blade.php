
<?php 
foreach($grid->columns() as $k=>$column) {
    $keys[$k] = $column->getLabel();
}

$rowData = [];
foreach($grid->rows() as $rIndex=>$row) {
    foreach($grid->columnNames as $k=>$name) {
        $rowData[$rIndex][$name] = $row->column($name);
    }
}
echo json_encode($rowData, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
?>

{{-- 此注释将不会出{!! $grid->paginator() !!}现在渲染后的 HTML --}}

