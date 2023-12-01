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
        'content' => 'object',
    ];

    public function page(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Content::class, 'content_id', 'id');
    }

    public function translate($field)
    {
        if(!is_object($field))
            return $field;

        if(isset($field->{app()->getLocale()}))
            return $field->{app()->getLocale()};

        if(isset($field->{app()->getFallbackLocale()}))
            return $field->{app()->getFallbackLocale()};

        return reset($field);
    }

    public function image($imageKey)
    {
        if($image = $this->page->getMediaByFieldKey($imageKey))
            return $image->getFullUrl();

        return '';
    }

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
