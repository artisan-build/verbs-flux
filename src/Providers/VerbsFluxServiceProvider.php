<?php

declare(strict_types=1);

namespace ArtisanBuild\VerbsFlux\Providers;

use ArtisanBuild\VerbsFlux\Actions\RedirectOnSuccess;
use ArtisanBuild\VerbsFlux\Contracts\RedirectsOnSuccess;
use ArtisanBuild\VerbsFlux\Livewire\FluxFormComponent;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class VerbsFluxServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../views', 'verbs-flux');
        Blade::anonymousComponentPath(__DIR__ . '/../../views/components');
        $this->app->bindIf(RedirectsOnSuccess::class, RedirectOnSuccess::class);
    }

    public function boot(): void
    {
        Livewire::component('event-form', FluxFormComponent::class);
    }
}
