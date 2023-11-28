<?php

namespace Thinmoto\LunarContent\Models;

use Lunar\Base\BaseModel;
use Spatie\MediaLibrary\HasMedia;
use Thinmoto\LunarContent\Traits\HasImages;

class ContentBlock extends BaseModel
{
    protected $table = 'content_blocks';

    public $timestamps = false;

    protected $fillable = [
        'alias',
        'kind',
        'content',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    public function getImagesKeys()
    {
        $out = [];

        if($this->kind == 'textblocks')
        {
            foreach($this->content as $key => $content)
                $out[] = 'content.'.$key.'.image';
        }

        return $out;
    }
}
