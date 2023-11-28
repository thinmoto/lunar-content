<?php

namespace Thinmoto\LunarContent\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\TemporaryUploadedFile;
use Lunar\Base\Traits\HasMedia;

trait HasImages
{
    use HasMedia;

    // protected static function bootHasImages()
    // {
    //     static::retrieved(function ($item) {
    //         $item->loadImages();
    //
    //         return $item;
    //     });
    //
    //     static::updated(function ($item) {
    //         $item->catchImages();
    //
    //         return $item;
    //     });
    //
    //     static::created(function ($item) {
    //         $item->catchImages();
    //
    //         return $item;
    //     });
    // }

    //abstract public function getImagesKeys();

    public function loadImages()
    {
        foreach($this->getImagesKeys() as $imageKey)
        {
            if($media = $this->getMediaByFieldKey($imageKey))
            {
                list($field, $fieldKey) = explode('.', $imageKey, 2);

                if(count(explode('.', $fieldKey)) > 1)
                {
                    $temp = Arr::dot($this->$field);
                    $temp[$fieldKey] = $media;
                    $this->$field = Arr::undot($temp);
                }
                else
                {
                    $this->$fieldKey = $media;
                }
            }
        }
    }

    public function catchImages()
    {
        foreach($this->getImagesKeys() as $imageKey)
        {
            list($field, $fieldKey) = explode('.', $imageKey, 2);

            if(count(explode('.', $fieldKey)) > 1)
            {
                $temp = Arr::dot($this->$field);

	            if(!isset($temp['withImagesImage']))
		            continue;

                $item = [
					'thumbnail' => $temp[$fieldKey.'.thumbnail'],
					'filename' => $temp[$fieldKey.'.filename'],
					'original' => $temp[$fieldKey.'.original'],
					'withImagesImage' => $temp[$fieldKey.'.withImagesImage'],
                ];
            }
            else
            {
                $item = $this->$fieldKey;

	            if(!isset($item['withImagesImage']))
		            continue;
            }

            if($media = $this->getMediaByFieldKey($imageKey))
                $media->delete();

            $file = TemporaryUploadedFile::createFromLivewire(
                $item['filename']
            );

            $filename = Str::of($file->getFilename())
                ->beforeLast('.')
                ->substr(0, 128)
                ->append('.', $file->getClientOriginalExtension());

            $media = $this->addMedia($file->getRealPath())
                ->usingFileName($filename)
                ->toMediaCollection('images')
                ->setCustomProperty('fieldKey', $imageKey);

            $media->save();
        }

        $this->loadImages();
    }

	public function getMediaByFieldKey($fieldKey)
	{
		if(!$this->media)
			return false;

		foreach($this->media as $image)
			if($image->getCustomProperty('fieldKey') == $fieldKey)
				return $image;

		return false;
	}
}
