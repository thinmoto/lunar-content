<?php

namespace Thinmoto\LunarContent\Http\Livewire\Lunar\Hub\Content;

use Livewire\Component;
use Thinmoto\LunarContent\Models\Content;

class ContentShow extends Component
{
    public Content $content;

    public function mount($content = null)
    {
        if(is_null($content))
            $this->content = new Content();
        else
            $this->content = $content;
    }

    function render()
    {
        return view('lunar-content::livewire.lunar.hub.content.show')
            ->layout('adminhub::layouts.app', [
                'title' => $this->content->name,
            ]);
    }
}
