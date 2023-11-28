<?php

namespace Thinmoto\LunarContent;

use Illuminate\Support\ServiceProvider;
use Lunar\Hub\Facades\Menu;

use Livewire\Livewire;

use Thinmoto\LunarContent\Http\Livewire\Lunar\Hub\Content\ContentIndex;
use Thinmoto\LunarContent\Http\Livewire\Lunar\Hub\Content\ContentPage;
use Thinmoto\LunarContent\Http\Livewire\Lunar\Hub\Content\ContentShow;
use Thinmoto\LunarContent\Http\Livewire\Lunar\Hub\Content\ContentTable;

class LunarContentServiceProvider extends ServiceProvider
{
	public function register(): void
	{

	}

	public function boot(): void
	{
        ## Routes

        include __DIR__.'/../routes/web.php';

        ## Views

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'lunar-content');

        ## Translations

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'lunarcontent');

        ## Livewire

        Livewire::component('lunar-content.content.index', ContentIndex::class);
        Livewire::component('lunar-content.content.table', ContentTable::class);
        Livewire::component('lunar-content.content.show', ContentShow::class);
        Livewire::component('lunar-content.content.page', ContentPage::class);

        ## Etc

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $slot = Menu::slot('sidebar');

        $slot->addItem(function ($item) {
            $item
                ->name(__('lunarcontent::content.hub.title'))
                ->route('lunar-content.content.index')
                ->icon('pencil-alt');
        });

        ## Publish

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'lunar-content.migrations');

        $this->publishes([
            __DIR__.'/../config/lunar-content.php' => config_path('lunar-content.php'),
        ], 'lunar-content.config');
	}
}
