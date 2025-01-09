<?php

declare(strict_types=1);

namespace ArtisanBuild\VerbsFlux\Attributes;

use ArtisanBuild\VerbsFlux\Enums\InputTypes;
use Attribute;
use BackedEnum;

#[Attribute(Attribute::TARGET_PROPERTY)]
class EventInput
{
    /**
     * @param  array|class-string<BackedEnum>  $options
     */
    public function __construct(
        // Required
        public InputTypes $type,
        public array $params = [],
        public ?string $label = null,
        public ?string $description = null,
        public ?string $badge = null,
        public ?string $mask = null,
        public ?string $icon = null,
        public ?string $icon_trailing = null,
        public ?string $keyboard = null,
        public ?string $placeholder = null,
        public ?string $prefix = null,
        public ?string $suffix = null,
        public ?bool $copyable = false,
        public ?bool $clearable = false,
        public ?bool $viewable = false,
        public ?bool $description_trailing = false,
        public int|string $rows = 4,
        public array $rules = [],
        public array|string $options = [],
        public ?string $options_filter = null,
    ) {}
}
