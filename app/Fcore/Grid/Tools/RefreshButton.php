<?php

namespace App\Fcore\Grid\Tools;

use App\Fcore\Fast;

class RefreshButton extends AbstractTool
{
    /**
     * Script for this tool.
     *
     * @return string
     */
    protected function script()
    {
        $message = trans('admin::lang.refresh_succeeded');

        return <<<EOT

$('.grid-refresh').on('click', function() {
    $.pjax.reload('#pjax-container');
    toastr.success('{$message}');
});

EOT;
    }

    /**
     * Render refresh button of grid.
     *
     * @return string
     */
    public function render()
    {
        Fast::script($this->script());

        $refresh = trans('admin::lang.refresh');

        return <<<EOT
<a class="btn btn-sm btn-primary grid-refresh"><i class="fa fa-refresh"></i> $refresh</a>
EOT;
    }
}
