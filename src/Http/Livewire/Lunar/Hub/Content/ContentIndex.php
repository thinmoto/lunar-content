<?php

namespace Thinmoto\LunarContent\Http\Livewire\Lunar\Hub\Content;

use Livewire\Component;

class ContentIndex extends Component
{
    function render()
    {
        return view('lunar-content::livewire.lunar.hub.content.index')
            ->layout('adminhub::layouts.app', [
                'title' => __('lunarcontent::content.hub.title'),
            ]);
    }
}
