<?php

declare(strict_types=1);

namespace ArtisanBuild\VerbsFlux\Actions;

use ArtisanBuild\VerbsFlux\Contracts\RedirectsOnSuccess;
use Illuminate\Database\Eloquent\Model;
use Thunk\Verbs\Event;

class RedirectOnSuccess implements RedirectsOnSuccess
{
    public function __invoke(Event $event, Model $success): ?string
    {
        return null;
    }
}
