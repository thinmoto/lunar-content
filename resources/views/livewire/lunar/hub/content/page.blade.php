<div class="space-y-4">
    <header>
        <div class="flex items-center gap-4">
            <a href="{{ route('lunar-content.content.index') }}"
               class="text-gray-600 rounded bg-gray-50 hover:bg-indigo-500 hover:text-white"
               title="{{ __('lunarcontent::content.hub.back') }}">
                <x-hub::icon ref="chevron-left"
                             style="solid"
                             class="w-8 h-8" />
            </a>
            <h1 class="text-xl font-bold md:text-xl">
                {{ $content->exists ? $content->name : __('lunarcontent::content.hub.creating') }}
            </h1>

            <div class="ml-auto uppercase">
                @foreach($this->languages as $language)
                    <x-hub::button tag="a"
                        wire:click="setCurrentLanguage('{{ $language->code }}')"
                        theme="{{ $this->currentLanguage->code == $language->code ? 'default' : 'gray' }}"
                    >
                        {{ $language->code }}
                    </x-hub::button>
                @endforeach
            </div>
        </div>
    </header>

    <x-hub::layout.bottom-panel>
        <form wire:submit.prevent="update">
            <div class="flex justify-end gap-4">
                <div class="ml-auto uppercase">
                    @foreach($this->languages as $language)
                        <x-hub::button tag="a"
                                       wire:click="setCurrentLanguage('{{ $language->code }}')"
                                       theme="{{ $this->currentLanguage->code == $language->code ? 'default' : 'gray' }}"
                        >
                            {{ $language->code }}
                        </x-hub::button>
                    @endforeach
                </div>

                <x-hub::button type="button" wire:click="$set('showDeleteConfirm', true)" theme="danger">
                    {{ __('adminhub::global.delete') }}
                </x-hub::button>

                <x-hub::button type="submit">
                    {{ __('adminhub::global.save') }}
                </x-hub::button>
            </div>
        </form>
    </x-hub::layout.bottom-panel>

    <div class="mt-8 lg:gap-8 lg:flex lg:items-start">
        <div class="space-y-6 lg:flex-1">

{{--            @foreach ($this->getSlotsByPosition('top') as $slot)
                <div id="{{ $slot->handle }}">
                    <div>
                        @livewire($slot->component, ['slotModel' => $brand], key('top-slot-' . $slot->handle))
                    </div>
                </div>
            @endforeach--}}

{{--            <div>
                @include('adminhub::partials.forms.brand')
            </div>--}}

            <div class="pb-24 mt-8 lg:gap-8 lg:flex lg:items-start">
                <div class="space-y-6 lg:flex-1">
                    <div id="basic-information">
                        <div class="space-y-4">
                            <div class="overflow-hidden shadow sm:rounded-md">
                                <div class="flex-col px-4 py-5 space-y-4 bg-white sm:p-6">
                                    <header>
                                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                                            {{ __('lunarcontent::content.hub.section_main') }}
                                        </h3>
                                    </header>

                                    <x-hub::input.group :label="__('lunarcontent::content.hub.name')" for="content.name" :error="$errors->first('content.name')">
                                        <x-hub::input.text
                                                wire:model="content.name"
                                                :error="$errors->first('content.name')"
                                        />
                                    </x-hub::input.group>

                                    <x-hub::input.group :label="__('lunarcontent::content.hub.template')" for="content.template" :error="$errors->first('content.template')">
                                        @if($this->content->exists)
                                            @foreach ($this->templates as $template => $options)
                                                @if($template == $this->content->template)
                                                    <x-hub::input.text
                                                            disabled
                                                            value="{{ __('lunarcontent::content.templates.'.$template) }}"
                                                            :error="$errors->first('content.name')"
                                                    />
                                                @endif
                                            @endforeach
                                        @else
                                            <x-hub::input.select
                                                    wire:model="content.template"
                                                    :error="$errors->first('content.template')"
                                            >
                                                @foreach ($this->templates as $template => $options)
                                                    <option value="{{ $template }}">{{ __('lunarcontent::content.templates.'.$template) }}</option>
                                                @endforeach
                                            </x-hub::input.select>
                                        @endif
                                    </x-hub::input.group>

                                    <x-hub::input.group :label="__('lunarcontent::content.hub.parent')" for="content.template" :error="$errors->first('content.parent_id')">
                                        <x-hub::input.select
                                                wire:model="content.parent_id"
                                                :error="$errors->first('content.template')"
                                        >
                                            <option value="">-</option>
                                            @foreach ($this->pages as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </x-hub::input.select>
                                    </x-hub::input.group>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($this->content->exists)
                        @foreach($blocks as $blockKey => $block)
                            <div id="section_{{ $blockKey }}" wire:key="content_block_{{ $blockKey }}">
                                <div class="space-y-4">
                                    <div class="overflow-hidden shadow sm:rounded-md">
                                        <div class="flex-col px-4 py-5 space-y-4 bg-white sm:p-6">
                                            @if($block['kind'] == 'wysiwyg')

                                                {{ $block['kind'] }}

                                            @elseif($block['kind'] == 'text')
                                                <header>
                                                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                                                        {{ isset($block['title']) ? __($block['title']) : __('lunarcontent::content.hub.section').' '.($blockKey+1) }}
                                                    </h3>
                                                </header>

                                                <x-hub::input.group label="" for="blocks.{{ $blockKey }}.content.text.{{ $this->currentLanguage->code }}">
                                                    <x-hub::input.text
                                                        wire:model="blocks.{{ $blockKey }}.content.text.{{ $this->currentLanguage->code }}"
                                                    />
                                                </x-hub::input.group>

                                            @elseif($block['kind'] == 'seo')
                                                <header>
                                                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                                                        SEO
                                                    </h3>
                                                </header>

                                                <x-hub::input.group :label="__('lunarcontent::content.hub.meta_title')" for="blocks.{{ $blockKey }}.meta_title.{{ $this->currentLanguage->code }}">
                                                    <x-hub::input.text
                                                            wire:model="blocks.{{ $blockKey }}.meta_title.{{ $this->currentLanguage->code }}"
                                                    />
                                                </x-hub::input.group>

                                                <x-hub::input.group :label="__('lunarcontent::content.hub.meta_description')" for="blocks.{{ $blockKey }}.meta_description.{{ $this->currentLanguage->code }}">
                                                    <x-hub::input.textarea
                                                            wire:model="blocks.{{ $blockKey }}.meta_description.{{ $this->currentLanguage->code }}"
                                                    />
                                                </x-hub::input.group>

                                                <x-hub::input.group :label="__('lunarcontent::content.hub.meta_keywords')" for="blocks.{{ $blockKey }}.meta_keywords.{{ $this->currentLanguage->code }}">
                                                    <x-hub::input.textarea
                                                            wire:model="blocks.{{ $blockKey }}.meta_keywords.{{ $this->currentLanguage->code }}"
                                                    />
                                                </x-hub::input.group>

                                            @elseif($block['kind'] == 'content')

                                                <header class="flex items-center justify-between">
                                                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                                                        {{ isset($block['title']) ? __($block['title']) : __('lunarcontent::content.hub.section').' '.($blockKey+1) }}
                                                    </h3>
                                                </header>

                                                <x-hub::input.group :label="__('lunarcontent::content.hub.title')" for="blocks.{{ $blockKey }}.content.title.{{ $this->currentLanguage->code }}">
                                                    <x-hub::input.text
                                                        wire:model="blocks.{{ $blockKey }}.content.title.{{ $this->currentLanguage->code }}"
                                                    />
                                                </x-hub::input.group>

                                                <x-hub::input.group :label="__('lunarcontent::content.hub.text')" for="blocks.{{ $blockKey }}.content.text.{{ $this->currentLanguage->code }}">
                                                    {{--<x-hub::input.richtext
                                                        wire:model.defer="blocks.{{ $blockKey }}.content.text.{{ $this->currentLanguage->code }}"
                                                        :initial-value="$block['content']['text'][$this->currentLanguage->code]"
                                                    />--}}

                                                    @foreach($this->languages as $language)
                                                        <div @class([
                                                            'hidden' => $language->code != $this->currentLanguage->code
                                                        ])>
                                                            <x-hub::input.richtext
                                                                    wire:model.defer="blocks.{{ $blockKey }}.content.text.{{ $language->code }}"
                                                                    :initial-value="$block['content']['text'][$language->code]"
                                                            />
                                                        </div>
                                                    @endforeach

                                                    {{--<x-hub::translatable>
                                                        <x-hub::input.richtext
                                                                wire:model.defer="blocks.{{ $blockKey }}.content.text.{{ $this->defaultLanguage->code }}"
                                                                :initial-value="$block['content']['text'][$this->defaultLanguage->code]"
                                                        />

                                                        @foreach($this->languages->filter(fn ($lang) => !$lang->default) as $language)
                                                            <x-slot :name="$language['code']">
                                                                <x-hub::input.richtext
                                                                        wire:model.defer="blocks.{{ $blockKey }}.content.text.{{ $language->code }}"
                                                                        :initial-value="$block['content']['text'][$language->code]"
                                                                />
                                                            </x-slot>
                                                        @endforeach
                                                    </x-hub::translatable>--}}
                                                </x-hub::input.group>

                                            @elseif($block['kind'] == 'textblocks')

                                                <header class="flex items-center justify-between">
                                                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                                                        {{ isset($block['title']) ? __($block['title']) : __('lunarcontent::content.hub.section').' '.($blockKey+1) }}
                                                    </h3>

                                                    <x-hub::button tag="a"
                                                       wire:click="addTextBlock({{ $blockKey }})"
                                                       theme="default"
                                                    >
                                                        {{ __('lunarcontent::content.hub.add_textblock') }}
                                                    </x-hub::button>
                                                </header>

                                                <div wire:sort
                                                     sort.options='{group: "textblocks_{{ $blockKey }}", method: "sort"}'
                                                     class="relative mt-4 space-y-2"
                                                >
                                                    @foreach($block['content'] as $key => $value)
                                                        <div class="flex items-center justify-between p-4 bg-white border rounded-md shadow-sm"
                                                             sort.item="textblocks_{{ $blockKey }}"
                                                             sort.id="{{ $key }}"
                                                             wire:key="textblocks_{{ $blockKey }}_{{ $key }}"
                                                        >
                                                            <div class="flex w-full space-x-6">
                                                                @if (count($block['content']) > 1)
                                                                    <div class="text-gray-400 cursor-grab hover:text-gray-700" sort.handle>
                                                                        <x-hub::icon ref="selector" style="solid" />
                                                                    </div>
                                                                @endif

                                                                @foreach($block['fields'] as $field)
                                                                    @if($field == 'thumb')
                                                                        @include('lunar-content::livewire.lunar.hub.content._one_image', ['imageKey' => $value['image'], 'label' => __('lunarcontent::content.hub.image')])
                                                                    @endif

                                                                    @if($field == 'title')
                                                                        <div class="w-full">
                                                                            <x-hub::input.group :label="__('lunarcontent::content.hub.title')" for="blocks.{{ $blockKey }}.content.{{ $key }}.title.{{ $this->currentLanguage->code }}">
                                                                                <x-hub::input.text
                                                                                    wire:model="blocks.{{ $blockKey }}.content.{{ $key }}.title.{{ $this->currentLanguage->code }}"
                                                                                />
                                                                            </x-hub::input.group>
                                                                        </div>
                                                                    @endif

                                                                    @if($field == 'text')
                                                                        <div class="w-full">
                                                                            <x-hub::input.group :label="__('lunarcontent::content.hub.text')" for="blocks.{{ $blockKey }}.content.{{ $key }}.text.{{ $this->currentLanguage->code }}">
                                                                                <x-hub::input.textarea
                                                                                    wire:model="blocks.{{ $blockKey }}.content.{{ $key }}.text.{{ $this->currentLanguage->code }}"
                                                                                />
                                                                            </x-hub::input.group>
                                                                        </div>
                                                                    @endif

                                                                    @if($field == 'url')
                                                                        <div class="w-full">
                                                                            <x-hub::input.group :label="__('lunarcontent::content.hub.url')" for="blocks.{{ $blockKey }}.content.{{ $key }}.url.{{ $this->currentLanguage->code }}">
                                                                                <x-hub::input.text
                                                                                    wire:model="blocks.{{ $blockKey }}.content.{{ $key }}.url.{{ $this->currentLanguage->code }}"
                                                                                />
                                                                            </x-hub::input.group>
                                                                        </div>
                                                                    @endif
                                                                @endforeach

                                                                <x-hub::tooltip :text="__('lunarcontent::content.hub.remove_block')">
                                                                    <button type="button"
                                                                            wire:click.prevent="removeTextBlock('{{ $blockKey }}', '{{ $key }}')"
                                                                            class="text-gray-400 hover:text-red-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                                                    >
                                                                        <x-hub::icon ref="trash"
                                                                                     style="solid"
                                                                                     class="text-gray-400 hover:text-red-500" />
                                                                    </button>
                                                                </x-hub::tooltip>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                            @elseif($block['kind'] == 'slider')

                                                <header class="flex items-center justify-between">
                                                    <h3 class="text-lg font-medium leading-6 text-gray-900">
                                                        {{ isset($block['title']) ? __($block['title']) : __('lunarcontent::content.hub.section').' '.($blockKey+1) }}
                                                    </h3>

                                                    <x-hub::button tag="a"
                                                                   wire:click="addSlide({{ $blockKey }})"
                                                                   theme="default"
                                                    >
                                                        {{ __('lunarcontent::content.hub.add_slide') }}
                                                    </x-hub::button>
                                                </header>

                                                <div wire:sort
                                                     sort.options='{group: "slider_{{ $blockKey }}", method: "sort"}'
                                                     class="relative mt-4 space-y-2"
                                                >
                                                    @foreach($block['content'] as $key => $value)
                                                        <div class="flex items-center justify-between p-4 bg-white border rounded-md shadow-sm"
                                                             sort.item="slider_{{ $blockKey }}"
                                                             sort.id="{{ $key }}"
                                                             wire:key="slider_{{ $blockKey }}_{{ $key }}"
                                                        >
                                                            <div class="flex w-full space-x-6">
                                                                @if (count($block['content']) > 1)
                                                                    <div class="text-gray-400 cursor-grab hover:text-gray-700" sort.handle>
                                                                        <x-hub::icon ref="selector" style="solid" />
                                                                    </div>
                                                                @endif

                                                                @include('lunar-content::livewire.lunar.hub.content._one_image', ['imageKey' => $value['image'], 'label' => __('lunarcontent::content.hub.image')])
                                                                @include('lunar-content::livewire.lunar.hub.content._one_image', ['imageKey' => $value['mobile_image'], 'label' => __('lunarcontent::content.hub.mobile_image')])

                                                                <div class="w-full">
                                                                    <x-hub::input.group :label="__('lunarcontent::content.hub.link')" for="blocks.{{ $blockKey }}.content.{{ $key }}.link">
                                                                        <x-hub::input.text
                                                                            wire:model="blocks.{{ $blockKey }}.content.{{ $key }}.link"
                                                                        />
                                                                    </x-hub::input.group>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    {{--<div id="attributes">
                        @include('adminhub::partials.attributes')
                    </div>

                    <div id="images">
                        @include('adminhub::partials.image-manager', [
                            'existing' => $images,
                            'wireModel' => 'imageUploadQueue',
                            'filetypes' => ['image/*'],
                        ])
                    </div>--}}

                    <div id="urls">
                        @include('adminhub::partials.urls')
                    </div>
                </div>

                <div class="sticky top-16">
                    <aside class="hidden h-full lg:block lg:flex-shrink-0">
                        <div class="flex flex-col overflow-y-auto bg-white rounded-md shadow w-72">
                            <div class="px-4 py-2">
                                <nav class="space-y-2" aria-label="Sidebar" x-data="{ activeAnchorLink: '' }" x-init="activeAnchorLink = window.location.hash">

                                    <a href="#basic-information" class="flex items-center gap-2 p-2 rounded text-gray-500 hover:bg-sky-50 hover:text-sky-700"
                                       aria-current="page"
                                       x-data="{ linkId: '#basic-information' }"
                                       :class="{
                                           'bg-sky-50 text-sky-700 hover:text-sky-500': linkId === activeAnchorLink
                                       }"
                                       x-on:click="activeAnchorLink = linkId"
                                    >

                                        <span class="text-sm font-medium">
                                            {{ __('lunarcontent::content.hub.section_main') }}
                                        </span>
                                    </a>

                                    @if($this->content->exists)
                                        @foreach($blocks as $blockKey => $block)
                                            <a href="#section_{{ $blockKey }}" class="flex items-center gap-2 p-2 rounded text-gray-500 hover:bg-sky-50 hover:text-sky-700"
                                               aria-current="page"
                                               x-data="{ linkId: '#section_{{ $blockKey }}' }"
                                               :class="{
                                           'bg-sky-50 text-sky-700 hover:text-sky-500': linkId === activeAnchorLink
                                       }"
                                               x-on:click="activeAnchorLink = linkId"
                                            >

                                                <span class="text-sm font-medium">
                                                    {{ isset($block['title']) ? __($block['title']) : __('lunarcontent::content.hub.section').' '.($blockKey+1) }}
                                                </span>
                                            </a>
                                        @endforeach
                                    @endif

                                    <a href="#urls" class="flex items-center gap-2 p-2 rounded text-gray-500 hover:bg-sky-50 hover:text-sky-700"
                                       aria-current="page"
                                       x-data="{ linkId: '#urls' }"
                                       :class="{
                                           'bg-sky-50 text-sky-700 hover:text-sky-500': linkId === activeAnchorLink
                                       }"
                                       x-on:click="activeAnchorLink = linkId"
                                    >

                                        <span class="text-sm font-medium">
                                            {{ __('adminhub::menu.urls') }}
                                        </span>
                                    </a>
                                </nav>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>


{{--            @foreach ($this->getSlotsByPosition('bottom') as $slot)
                <div id="{{ $slot->handle }}">
                    <div>
                        @livewire($slot->component, ['slotModel' => $brand], key('top-slot-' . $slot->handle))
                    </div>
                </div>
            @endforeach--}}
        </div>
    </div>

    <x-hub::modal.dialog wire:model="showDeleteConfirm">
        <x-slot name="title">
            {{ __('adminhub::global.delete') }}
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <p>
                    confirm delete xxx
                </p>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-hub::button type="button"
                           wire:click.prevent="$set('showDeleteConfirm', false)"
                           theme="gray">
                {{ __('adminhub::global.cancel') }}
            </x-hub::button>

            <x-hub::button type="button" wire:click="deleteContent" theme="danger">
                {{ __('adminhub::global.delete') }}
            </x-hub::button>
        </x-slot>
    </x-hub::modal.dialog>

    <style>
        .image-upload
        {
            min-width: 130px;
        }
    </style>
</div>
