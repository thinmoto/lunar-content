<?php

namespace Thinmoto\LunarContent\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\TemporaryUploadedFile;
use function Laravel\Prompts\error;

trait WithImages
{
    public $withImages = [];
    public $withImagesImage = [];

    abstract public function getImagesKeys();

    public function withImagesListener()
    {
        return [
            'upload:finished' => 'uploadWithImagesListener'
        ];
    }

    public function mountWithImages()
    {
        foreach($this->getWithImagesModel()->media as $media)
        {
            $this->withImages[$media->getCustomProperty('fieldKey')] = [
                'thumbnail' => $media->getFullUrl(),
                //'filename' => $filename,
                'original' => $media->getFullUrl(),
                'withImagesImage' => true,
            ];
        }
    }

    public function uploadWithImagesListener($name, $filenames)
    {
        if (strpos($name, 'uploadWithImages') < 0)
            return;

        if ($this->errorBag->count())
            return;

        list(, $fullFiledKey) = explode('.', $name, 2);
        //list($field, $fieldKey) = explode('.', $fullFiledKey, 2);

        // if(!isset($this->$field))
        // {
        //     dump('not found field named "'.$field.'" in component');
        //     exit();
        // }

        $tempFile = null;

        foreach ($filenames as $fileKey => $filename)
        {
            $file = TemporaryUploadedFile::createFromLivewire($filename);

            $this->withImages[$fullFiledKey] = [
                'thumbnail' => $file->temporaryUrl(),
                'filename' => $filename,
                'original' => $file->temporaryUrl(),
                'withImagesImage' => true,
            ];

            break;
        }

        // if(gettype($this->$field) == 'string' || (gettype($this->$field) == 'array' && isset($this->$field['withImagesImage'])))
        // {
        //     $this->$field = $fullFiledKey;
        // }
        // elseif(gettype($this->$field) == 'array')
        // {
        //     $temp = Arr::dot($this->$field);
        //     $temp[$fieldKey] = $fullFiledKey;
        //     $this->$field = Arr::undot($temp);
        // }
        // else
        // {
        //     dump('only array or string fields supported');
        //     exit();
        // }

        $this->reset('withImagesImage');
    }

    public function saveWithImages()
    {
        $leaveKeys = [];

        foreach($this->withImages as $fieldKey => $fieldValues)
        {
			if(isset($this->withImages[$fieldKey]['filename'])) // new file
			{
				if($media = $this->getWithImagesModel()->getMediaByFieldKey($fieldKey))
					$media->delete();
                
				$file = TemporaryUploadedFile::createFromLivewire(
					$this->withImages[$fieldKey]['filename']
				);

				$filename = Str::of($file->getFilename())
					->beforeLast('.')
					->substr(0, 128)
					->append('.', $file->getClientOriginalExtension());

				if(file_exists($file->getRealPath()))
				{
					$media = $this->getWithImagesModel()->addMedia($file->getRealPath())
						->usingFileName($filename)
						->toMediaCollection('images')
						->setCustomProperty('fieldKey', $fieldKey);

					$media->save();
				}
			}

            $leaveKeys[] = $fieldKey;
        }

        foreach($this->getWithImagesModel()->media as $media)
            if(!in_array($media->getCustomProperty('fieldKey'), $leaveKeys))
                $media->delete();
    }

    public function removeWithImagesImage($imageKey)
    {
        if(isset($this->withImages[$imageKey]))
            unset($this->withImages[$imageKey]);
    }
}
