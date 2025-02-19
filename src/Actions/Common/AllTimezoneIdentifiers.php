<?php

declare(strict_types=1);

namespace ArtisanBuild\VerbsFlux\Actions\Common;

class AllTimezoneIdentifiers
{
    public function __invoke(): array
    {
        return timezone_identifiers_list();
    }
}
