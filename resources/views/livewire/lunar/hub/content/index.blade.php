<div class="flex-col space-y-4">
    <div class="items-center justify-between md:flex">
        <strong class="block text-lg font-bold md:text-2xl">
            {{ __('lunarcontent::content.hub.title') }}
        </strong>

        <div class="text-right">
            <x-hub::button tag="a" href="{{ route('lunar-content.content.create') }}">
                {{ __('lunarcontent::content.hub.add') }}
            </x-hub::button>
        </div>
    </div>

    @livewire('lunar-content.content.table')
</div>
