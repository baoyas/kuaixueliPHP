<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Str;

class SwitchField extends Field
{
    protected static $css = [
        '/packages/admin/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css',
    ];

    protected static $js = [
        '/packages/admin/bootstrap-switch/dist/js/bootstrap-switch.min.js',
    ];

    protected $states = [
        'on'  => ['value' => 1, 'text' => 'ON', 'color' => 'primary'],
        'off' => ['value' => 0, 'text' => 'OFF', 'color' => 'default'],
    ];

    protected $tshowScript = '';

    public function states($states = [])
    {
        foreach (array_dot($states) as $key => $state) {
            array_set($this->states, $key, $state);
        }

        return $this;
    }

    public function prepare($value)
    {
        if (isset($this->states[$value])) {
            return $this->states[$value]['value'];
        }

        return $value;
    }

    public function render()
    {
        foreach ($this->states as $state => $option) {
            if ($this->value() == $option['value']) {
                $this->value = $state;
                break;
            }
        }

        $this->script = <<<EOT

$('{$this->getElementClassSelector()}.la_checkbox').bootstrapSwitch({
    size:'small',
    onText: '{$this->states['on']['text']}',
    offText: '{$this->states['off']['text']}',
    onColor: '{$this->states['on']['color']}',
    offColor: '{$this->states['off']['color']}',
    onSwitchChange: function(event, state) {
        $('{$this->getElementClassSelector()}').val(state ? 'on' : 'off');
        {$this->tshowScript}
    }
});

EOT;

        return parent::render();
    }

    /**
     * Load options for other select on change.
     *
     * @param string $field
     * @param string $sourceUrl
     * @param string $idField
     * @param string $textField
     *
     * @return $this
     */
    public function tshow($field, $showKey='on')
    {
        if (Str::contains($field, '.')) {
            $field = $this->formatName($field);
            $class = str_replace(['[', ']'], '_', $field);
        } else {
            $class = $field;
        }

        $this->tshowScript = <<<EOT
    var target = $(this).closest('.fields-group').find(".$class").closest('.form-group');
    if($('{$this->getElementClassSelector()}').val()=='$showKey') {
        $(target).show();
    } else {
        $(target).hide();
    }
EOT;

        return $this;
    }
}
