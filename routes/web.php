<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Lunar\Hub\Http\Livewire\Hub;
use Lunar\Hub\Http\Livewire\Pages\Account;
use Lunar\Hub\Http\Livewire\Pages\Authentication\Login;
use Lunar\Hub\Http\Livewire\Pages\Authentication\PasswordReset;
use Lunar\Hub\Http\Middleware\Authenticate;
use Lunar\Hub\Http\Middleware\RedirectIfAuthenticated;

use Thinmoto\LunarContent\Http\Livewire\Lunar\Hub\Content\ContentIndex;
use Thinmoto\LunarContent\Http\Livewire\Lunar\Hub\Content\ContentShow;

Route::group([
    'prefix' => config('lunar-hub.system.path', 'hub'),
    'middleware' => config('lunar-hub.system.middleware', ['web']),
], function () {
    Route::group([
        'middleware' => [
            Authenticate::class,
        ],
    ], function ($router) {
        Route::group([
            'prefix' => 'content',
        ], function($router){
            Route::group([
                'middleware' => 'can:catalogue:manage-products',
            ], function () {
                Route::get('/', ContentIndex::class)->name('lunar-content.content.index');
                Route::get('/create', ContentShow::class)->name('lunar-content.content.create');
                Route::get('/{content}', ContentShow::class)->name('lunar-content.content.show');
            });
        });
    });
});
