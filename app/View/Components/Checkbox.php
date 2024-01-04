<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Checkbox extends Component
{
    public $checked;
    public $name;
    public $label;


    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($checked, $name, $label)
    {
        $this->checked = $checked;
        $this->name = $name;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render(){
        return <<<'blade'
            <label class="switch">
            <input type="checkbox" {!! $checked ? 'checked':'' !!} name="{!! $name !!}" value="1" class="switch-input">
            <span class="switch-toggle-slider">
                <span class="switch-on">
                    <i class="ti ti-check"></i>
                </span>
                <span class="switch-off">
                    <i class="ti ti-x"></i>
                </span>
            </span>
            <span class="switch-label">{!! $label !!}</span>
            </label>
        blade;
    }
}
