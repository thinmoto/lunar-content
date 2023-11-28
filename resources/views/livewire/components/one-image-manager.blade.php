@php
    $modelKey = isset($modelKey) ? $modelKey : 'images';
    $imageKey = isset($imageKey) ? $imageKey : false;
    $hide = true;
@endphp

@if(
    ($imageKey === false && !count($this->{$modelKey}))
    || ($imageKey !== false && !isset($this->{$modelKey}[$imageKey]))
)
    @php
        $hide = false;
    @endphp
@endif

<div style="@if($hide)
    display:none
@endif">
    <x-hub::input.fileupload wire:model="uploadOneImage.{{ $imageKey }}"
                             :filetypes="$filetypes"
    />
</div>

@if ($errors->has('uploadOneImage.'.$imageKey))
    <x-hub::alert level="danger">
        {{ __('adminhub::partials.image-manager.generic_upload_error') }}
    </x-hub::alert>
@endif

<div
    @if(isset($inline) && $inline)
        classs="inline-flex"
    @endif
>
    <div class="relative mt-4 space-y-2">
        @foreach ($this->uploadedImages as $key => $image)
            @if($key == $imageKey)
                <div class="flex items-center justify-between p-4 bg-white border rounded-md shadow-sm"
                     wire:key="image_{{ $imageKey }}_{{ rand() }}">
                    <div class="flex items-center w-full space-x-6">
                        <div class="flex flex-shrink-0" x-data="{ imageBlob: null }">
                            @if($this->uploadedImages[$imageKey]['thumbnail'] )
                                <button type="button"
                                        wire:click="$set('{{ $modelKey }}.{{ $imageKey }}.preview', true)">
                                    <x-hub::thumbnail :src="$image['thumbnail']" />
                                </button>
                            @endif

                            @if($this->uploadedImages[$imageKey]['preview'] )
                                <x-hub::modal wire:model="{{ $modelKey }}.{{ $imageKey }}.preview">
                                    <img src="{{ $image['original'] }}">
                                </x-hub::modal>
                            @endif

                            @if($this->uploadedImages[$imageKey]['edit'])
                                <x-hub::modal wire:model="{{ $modelKey }}.{{ $imageKey }}.edit" max-width="5xl">
                                    <div
                                        x-data="{
                                    filerobotImageEditor: null,

                                    init() {
                                        const { TABS, TOOLS } = FilerobotImageEditor;
                                        const config = {
                                            source: imageBlob ? imageBlob : '{{ $image['original'] }}',
                                            Rotate: { angle: 45, componentType: 'slider' },
                                            theme: {
                                                typography: {
                                                  fontFamily: 'Nunito, Arial',
                                                },
                                            }
                                        }

                                        filerobotImageEditor = new FilerobotImageEditor($el, config);

                                        filerobotImageEditor.render({
                                            onClose: (closingReason) => {
                                                @this.set('images.{{ $imageKey }}.edit', false)

                                                filerobotImageEditor.terminate();
                                            },
                                            onBeforeSave: (imageFileInfo) => false,
                                            onSave: (imageData, imageDesignState) => {

                                                imageBlob = imageData.imageBase64

                                                fetch(imageData.imageBase64)
                                                    .then(res => res.blob())
                                                    .then(blob => {
                                                        const file = new File([blob], imageData.fullName,{ type: imageData.mimeType })

                                                        @this.upload('images.{{ $imageKey }}.file', file)

                                                        @this.set('images.{{ $imageKey }}.edit', false)

                                                        @this.set('images.{{ $imageKey }}.thumbnail', imageData.imageBase64)

                                                        @this.set('images.{{ $imageKey }}.original', imageData.imageBase64)
                                                    })
                                            }
                                        });
                                    }
                                }"

                                    >
                                    </div>
                                </x-hub::modal>
                            @endif
                        </div>

                        @if(!isset($inline) || !$inline)
                            <div class="w-full">
                                <x-hub::input.text wire:model="{{ $modelKey }}.{{ $imageKey }}.caption"
                                                   placeholder="Enter Alt. text" />
                            </div>
                        @endif

                        <div class="flex items-center ml-4 space-x-4">
                            @if (!empty($image['id']))
                                <x-hub::tooltip :text="__('adminhub::partials.image-manager.remake_transforms')">
                                    <button wire:click.prevent="regenerateConversions('{{ $image['id'] }}')"
                                            href="{{ $image['original'] }}"
                                            type="button">
                                        <x-hub::icon ref="refresh"
                                                     style="solid"
                                                     class="text-gray-400 hover:text-indigo-500" />
                                    </button>
                                </x-hub::tooltip>
                            @endif

                            <button type="button"
                                    wire:click="$set('{{ $modelKey }}.{{ $imageKey }}.edit', true)">
                                <x-hub::icon ref="pencil"
                                             style="solid"
                                             class="text-gray-400 hover:text-indigo-500" />
                            </button>

                            <x-hub::tooltip :text="__('Remove image')">
                                <button type="button"
                                        wire:click.prevent="removeOneImage('{{ $imageKey !== false ? $imageKey : $imageKey }}', 'uploadOneImage.{{ $imageKey }}')"
                                        class="text-gray-400 hover:text-red-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <x-hub::icon ref="trash"
                                                 style="solid"
                                                 class="text-gray-400 hover:text-red-500" />
                                </button>
                            </x-hub::tooltip>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
