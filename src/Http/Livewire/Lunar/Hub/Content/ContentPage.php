<?php

namespace Thinmoto\LunarContent\Http\Livewire\Lunar\Hub\Content;

use Livewire\Component;
use Livewire\WithFileUploads;
use Lunar\Hub\Http\Livewire\Traits\HasUrls;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use Thinmoto\LunarContent\Models\Content;
use Thinmoto\LunarContent\Traits\WithLanguages;
use Thinmoto\LunarContent\Traits\WithImages;

class ContentPage extends Component
{
    use HasUrls;
    use Notifies;
    use WithLanguages;
    use WithFileUploads;
    use WithImages;

    public Content $content;
    public $blocks = [];

    public $showDeleteConfirm = false;

    public function mount()
    {
        $this->remapContentBlocks();

        $this->mountHasUrls();
        $this->mountWithImages();
    }

public function render()
{
    return view('lunar-content::livewire.lunar.hub.content.page')
            ->layout('adminhub::layouts.base');
}

protected function getListeners()
{
    return array_merge(
        [],
            $this->withImagesListener(),
        );
}

public function getHasUrlsModel()
{
    return $this->content;
}

public function getWithImagesModel()
{
    return $this->content;
}

public function getImagesKeys()
{
    $out = [];

    foreach($this->template as $blockKey => $block)
    {
        if($block['kind'] == 'textblocks')
        {
            foreach($this->blocks[$blockKey]['content'] as $key => $content)
            {
                $out[] = 'blocks.' . $blockKey . '.content.' . $key . '.image';
                $out[] = 'blocks.' . $blockKey . '.content.' . $key . '.small_image';
            }
        }
    }

        return $out;
}

public function getTemplatesProperty()
{
    return config('lunar-content.templates', []);
}

public function getTemplateProperty()
{
    return $this->templates[$this->content->template];
}

public function getPagesProperty()
{
    $query = Content::query();

    if($this->content->exists)
        $query->where('id', '<>', $this->content->id);

    return $query->pluck('name', 'id');
}

public function updatedContentTemplate()
{
    $this->remapContentBlocks();
}

protected function remapContentBlocks()
{
    $this->blocks = [];

    foreach($this->template as $k => $templateBlock)
    {
        $templateBlock['content'] = '';

        if(!isset($templateBlock['fields']))
            $templateBlock['fields'] = [];

        switch($templateBlock['kind'])
            {
                case 'text':
                    $templateBlock['content'] = [
                        'text' => $this->emptyLang(),
                    ];
                    break;

                    case 'content':
                        $templateBlock['content'] = [
                            'title' => $this->emptyLang(),
                        'text' => $this->emptyLang(),
                    ];
                        break;

                        case 'textblocks':
                            $templateBlock['content'] = [];

                            if(isset($templateBlock['count']))
                                for($i = 0; $i < $templateBlock['count']; $i++)
                                    $templateBlock['content'][$i] = [
                                        'state' => true,
                                'image' => uuid_create(),
                                'mobile_image' => uuid_create(),
                                'text' => $this->emptyLang(),
                            ];
                            break;

                            case 'slider':
                                $templateBlock['content'] = [];
                                break;
            }

            if($modelBlock = $this->content->block($templateBlock['alias']))
                if($modelBlock->kind == $templateBlock['kind'] && $modelBlock->content)
                    $templateBlock['content'] = json_decode(json_encode($modelBlock->content), true);;

                    if($templateBlock['kind'] == 'textblocks')
                    {
                        foreach($templateBlock['content'] as &$line)
                        {
                            if(!isset($line['state']))
                                $line['state'] = true;

                            if(!isset($line['mobile_image']))
                                $line['mobile_image'] = uuid_create();
                        }
                    }

	        if($templateBlock['kind'] == 'slider')
            {
                foreach($templateBlock['content'] as &$line)
                {
                    if(!isset($line['state']))
                        $line['state'] = true;
                }
            }

            $this->blocks[$k] = $templateBlock;
    }
}

protected function rules()
{
    $allowedTemplates = array_keys($this->templates);
    $allowedPages = array_keys($this->templates);

    $rules = [
        'content.name' => 'required|string',
            'content.template' => 'required|string|in:'.implode(',', $allowedTemplates),
            'content.parent_id' => 'nullable|string|in:'.implode(',', $allowedPages),
        ];

    return array_merge(
        $rules,
            $this->hasUrlsValidationRules(true)
        );
}

public function update()
{
    $this->validate();
    $this->validateUrls();

    $this->content->save();

    $this->saveWithImages();
    $this->content->blocks()->sync($this->blocks);

    $this->saveUrls();

    //$this->updateSlots();

        $this->notify(
            __('lunarcontent::content.hub.saved'),
            'lunar-content.content.show',
            ['content' => $this->content]
        );
}

public function sort($event)
{
    $out = [];
    list($kind, $blockNum) = explode('_', $event['group']);

    foreach($event['items'] as $order => $item)
        $out[$order] = $this->blocks[$blockNum]['content'][$item['id']];

    $this->blocks[$blockNum]['content'] = $out;
}

public function addTextBlock($blockKey)
{
    $this->blocks[$blockKey]['content'][] = [
        'image' => uuid_create(),
            'mobile_image' => uuid_create(),
            'text' => $this->emptyLang(),
        ];
}

public function removeTextBlock($blockKey, $sectionKey)
{
    if(isset($this->blocks[$blockKey]['content'][$sectionKey]))
        unset($this->blocks[$blockKey]['content'][$sectionKey]);
}

public function addSlide($blockKey)
{
    $this->blocks[$blockKey]['content'][] = [
        'state' => true,
            'image' => uuid_create(),
            'mobile_image' => uuid_create(),
            'link' => '',
        ];
}

public function deleteContent()
{
    $this->content->delete();

    $this->notify(
        __('lunarcontent::content.hub.deleted'),
            'lunar-content.content.index'
        );
}

private function emptyLang()
{
    $out = [];

    foreach($this->languages as $language)
        $out[$language->code] = '';

    return $out;
}
}
