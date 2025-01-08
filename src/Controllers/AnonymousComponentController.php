<?php

declare(strict_types=1);

namespace ArtisanBuild\VerbsFlux\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\View;
use Illuminate\Contracts\View\View as ViewContract;

class AnonymousComponentController
{
    public function __invoke(): Application|ViewContract|Factory|null
    {
        return View::make('verbs-flux::anonymous');
    }
}
