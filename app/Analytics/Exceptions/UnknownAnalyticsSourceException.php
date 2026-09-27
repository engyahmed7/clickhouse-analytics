<?php

namespace App\Analytics\Exceptions;

use InvalidArgumentException;

class UnknownAnalyticsSourceException extends InvalidArgumentException
{
    public static function for(string $source, array $available): self
    {
        $availableList = $available === []
            ? '(none configured)'
            : implode(', ', $available);

        return new self(
            "Unknown analytics source [{$source}]. Available sources: {$availableList}."
        );
    }
}
