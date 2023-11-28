<?php

namespace Thinmoto\LunarContent\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Lunar\Base\BaseModel;
use Lunar\Base\Traits\HasUrls;
use Spatie\MediaLibrary\HasMedia;
use Thinmoto\LunarContent\Traits\HasImages;
use Thinmoto\LunarContent\Traits\HasManySyncable;

class Content extends BaseModel implements HasMedia
{
    use HasUrls;
    use SoftDeletes;
    use HasManySyncable;
    use HasImages;

    protected $table = 'content';

    protected $attributes = [
        'template' => 'default',
    ];

    public function blocks()
    {
        return $this->hasManySyncable(ContentBlock::class, 'content_id', 'id');
    }

    public function block($key)
    {
        foreach($this->blocks as $k => $block)
            if($k == $key)
                return $block;

        return null;
    }
}
