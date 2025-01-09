<?php

declare(strict_types=1);

namespace ArtisanBuild\VerbsFlux\Enums;

enum ButtonVariants: string
{
    case Default = 'default';
    case Disabled = 'disabled';
    case Primary = 'primary';
    case Filled = 'filled';
    case Danger = 'danger';
    case Ghost = 'ghost';

    // Return the Flux variant name
    public function flux(): string
    {
        return match ($this) {
            self::Default => 'danger',
            self::Filled => 'filled',
            self::Ghost => 'ghost',
            self::Primary => 'primary',
            default => 'outline',
        };
    }

    public function class(): string
    {
        $classes = match ($this) {
            self::Disabled => ['cursor-not-allowed'],
            default => [],
        };

        return implode(' ', array_filter($classes));
    }
}
