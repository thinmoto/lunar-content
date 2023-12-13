<div wire:key="withimages_{{ $imageKey }}" x-data="{images:@entangle('withImages'),imageKey:'{{ $imageKey }}'}" x-init="console.log(typeof(images[imageKey]))">
    <x-hub::input.group :label="$label" for="blocks.{{ $blockKey }}.content.{{ $imageKey }}" x-show="typeof(images[imageKey] != 'undefined')">
        <div class="flex">
            @if(isset($withImages[$imageKey]))
            <button type="button">
                <x-hub::thumbnail :src="$withImages[$imageKey]['thumbnail']" />
            </button>

            <x-hub::tooltip :text="__('lunarcontent::content.hub.remove_image')">
                <button type="button"
                        wire:click.prevent="removeWithImagesImage('{{ $imageKey }}')"
                        class="text-gray-400 hover:text-red-500 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <x-hub::icon ref="trash"
                                 style="solid"
                                 class="text-gray-400 hover:text-red-500" />
                </button>
            </x-hub::tooltip>
                @endif
        </div>
    </x-hub::input.group>

    <div x-show="typeof(images[imageKey]) == 'undefined'">
        <div class="image-upload mt-1">
            <x-hub::input.fileupload wire:model="withImagesImage.{{ $imageKey }}"
                {{--:filetypes="$filetypes"--}}
            />
        </div>
    </div>
</div>
