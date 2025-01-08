<?php

declare(strict_types=1);

namespace ArtisanBuild\VerbsFlux\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_ALL)]
class EventForm
{
    public function __construct(
        public string $submit_text = 'Save',
        public string $success = 'Saved!',
        public string $failure = 'Oops. Something went wrong',
        public ?string $on_success = null,
    ) {}
}
