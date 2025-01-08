<?php

declare(strict_types=1);

namespace ArtisanBuild\VerbsFlux\Enums;

enum InputTypes: string
{
    case Button = 'button';
    case Checkbox = 'checkbox';
    case Color = 'color';
    case Date = 'date';
    case DatetimeLocal = 'datetime-local';
    case Email = 'email';
    case File = 'file';
    case Hidden = 'hidden';
    case Image = 'image';
    case Month = 'month';
    case Number = 'number';
    case Password = 'password';
    case Radio = 'radio';
    case Range = 'range';
    case Reset = 'reset';
    case Search = 'search';
    case Select = 'select';
    case Submit = 'submit';
    case Tel = 'tel';
    case Text = 'text';
    case Textarea = 'textarea';
    case Time = 'time';
    case Url = 'url';
    case Week = 'week';

    public function allowed(): array
    {
        $universal = [
            'id', 'class', 'style', 'title', 'name', 'value', 'type', 'disabled', 'readonly',
            'required', 'autofocus', 'autocomplete', 'tabindex', 'maxlength', 'minlength',
            'pattern', 'placeholder', 'spellcheck', 'form', 'formaction', 'formenctype',
            'formmethod', 'formnovalidate', 'formtarget',
        ];

        $allowed = match ($this) {
            self::Text => ['list', 'maxlength', 'minlength', 'size', 'inputmode'],
            self::Checkbox => ['indeterminate'],
            self::Date, self::Week, self::Time, self::Month, self::DatetimeLocal => ['min', 'max', 'step'],
            self::Email => ['multiple', 'maxlength', 'minlength', 'size', 'pattern', 'list'],
            self::File => ['accept', 'multiple'],
            self::Image => ['alt', 'src', 'width', 'height', 'formaction', 'formenctype', 'formmethod', 'formnovalidate', 'formtarget'],
            self::Number, self::Range => ['min', 'max', 'step', 'list'],
            self::Password => ['maxlength', 'minlength', 'size', 'pattern'],
            self::Radio => ['checked'],
            self::Search => ['list', 'maxlength', 'minlength', 'size'],
            self::Select => ['multiple'],
            self::Submit => ['formaction', 'formenctype', 'formmethod', 'formnovalidate', 'formtarget'],
            self::Tel, self::Url => ['maxlength', 'minlength', 'size', 'pattern', 'list'],
            default => [],
        };

        return array_merge($universal, $allowed);
    }
}
