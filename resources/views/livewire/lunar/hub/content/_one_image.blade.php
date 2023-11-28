<div wire:key="withimages_{{ $imageKey }}">
    @if(isset($withImages[$imageKey]))
        <x-hub::input.group :label="$label" for="blocks.{{ $blockKey }}.content.{{ $imageKey }}">
            <div class="flex">
                <button type="button">
                    <x-hub::thumbnail :src="$withImages[$imageKey]['thumbnail']" />
                </button>

                <x-hub::tooltip :text="__('Remove image')">
                    <button type="button"
                            wire:click.prevent="removeWithImagesImage('{{ $imageKey }}')"
                            class="text-gray-400 hover:text-red-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <x-hub::icon ref="trash"
                                     style="solid"
                                     class="text-gray-400 hover:text-red-500" />
                    </button>
                </x-hub::tooltip>
            </div>
        </x-hub::input.group>
    @else
        <div>
            <label class="flex items-center text-sm font-medium text-gray-700">
                {{ __('lunarcontent::content.hub.image') }}
            </label>

            <div class="image-upload mt-1">
                <x-hub::input.fileupload wire:model="withImagesImage.{{ $imageKey }}"
                    {{--:filetypes="$filetypes"--}}
                />
            </div>
        </div>
    @endif
</div>
