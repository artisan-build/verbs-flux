<?php

declare(strict_types=1);

use ArtisanBuild\VerbsFlux\Controllers\AnonymousComponentController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->get('verbs-flux/anonymous', AnonymousComponentController::class);
