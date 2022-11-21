<?php
declare(strict_types=1);

namespace Core\Domain\ValueObject;

use DomainException;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid as RamseyUuid;

final class Uuid
{
    public function __construct(
        private string $value,
    ) {
        $this->ensureIsValid($value);
    }

    public function ensureIsValid(string $id)
    {
        if (!RamseyUuid::isValid($id)) {
            throw new InvalidArgumentException(sprintf('<%s> does not allow the value <%s>', static::class, $id));
        }
    }

    public static function random(): self
    {
        return new self(RamseyUuid::uuid4()->toString());
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
