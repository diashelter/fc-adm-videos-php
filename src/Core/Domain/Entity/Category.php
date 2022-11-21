<?php
declare(strict_types=1);

namespace Core\Domain\Entity;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Core\Domain\ValueObject\Uuid;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\Entity\Traits\MethodsMagicsTrait;

final class Category
{
    use MethodsMagicsTrait;

    public function __construct(
        protected Uuid|string $id = '',
        protected string $name = '',
        protected string $description = '',
        protected bool $isActive = false,
        protected DateTimeInterface $createdAt = new DateTimeImmutable()
    ) {
        $this->id = $this->id ? new Uuid($this->id) : Uuid::random();
        $this->validate();
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function deactivate(): void
    {
        $this->isActive = false;
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function update(string $name, string $description)
    {
        $this->name = $name;
        $this->description = $description;

        $this->validate();
    }

    private function validate()
    {
        DomainValidation::notNull($this->name, 'name is required');
        DomainValidation::strMinLength($this->name, 3, 'name caracteres should grant 3');
        DomainValidation::strCanNullAndMaxLength($this->description, 255, 'description caracteres entry 3 and 255');
    }
}
