<?php

declare(strict_types=1);

namespace Core\Domain\Validation;

use Core\Domain\Exception\EntityValidationException;

final class DomainValidation
{
    public static function notNull(string $value, ?string $exceptionMessage = null)
    {
        if (empty($value)) {
            throw new EntityValidationException($exceptionMessage ?? 'Should not be empty');
        }
    }

    public static function strMaxLength(string $value, int $length = 255, ?string $exceptionMessage = null)
    {
        if (strlen($value) > $length) {
            throw new EntityValidationException($exceptionMessage ?? "The value must not be greater than {$length} caracteres");
        }
    }

    public static function strMinLength(string $value, int $length = 3, ?string $exceptionMessage = null)
    {
        if (strlen($value) < $length) {
            throw new EntityValidationException($exceptionMessage ?? "The value must not be minor than {$length} caracteres");
        }
    }

    public static function strCanNullAndMaxLength(string $value, int $length = 255, ?string $exceptionMessage = null)
    {
        if (! empty($value) && strlen($value) > $length) {
            throw new EntityValidationException($exceptionMessage ?? "The value must not be greater than {$length} caracteres");
        }
    }
}
