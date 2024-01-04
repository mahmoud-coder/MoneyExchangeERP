<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Card extends Component
{
    public $title;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title)
    {
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return <<<'blade'
            <div {!! $attributes->merge(['class' => 'card']) !!}>
                <div class="card-body">
                    <h5 class="card-title">{!! $title !!}</h5>
                    {{$slot}}
                </div>
            </div>
        blade;
    }
}
