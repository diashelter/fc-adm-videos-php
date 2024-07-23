<?php

declare(strict_types=1);

namespace Core\Domain\Entity\Traits;

use DomainException;

trait MethodsMagicsTrait
{
    public function __get($property)
    {
        if (! isset($this->{$property})) {
            $className = get_class($this);
            throw new DomainException("Property {$property} not found in class {$className}");
        }

        return $this->{$property};
    }

    public function id(): string
    {
        return (string) $this->id;
    }

    public function createdAt(): string
    {
        return $this->createdAt->format('Y-m-d H:i:s');
    }
}
