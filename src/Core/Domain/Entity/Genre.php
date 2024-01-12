<?php
declare(strict_types=1);

namespace Core\Domain\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Core\Domain\ValueObject\Uuid;
use Core\Domain\Entity\Traits\MethodsMagicsTrait;
use Core\Domain\Validation\DomainValidation;

final class Genre
{
    use MethodsMagicsTrait;

    public function __construct(
        private ?Uuid $id = null,
        private string $name = '',
        private bool $isActive = true,
        private array $categoriesId = [],
        private DateTimeInterface $createdAt = new DateTimeImmutable()
    ) {
        $this->id = $this->id ?? Uuid::random();
        $this->validate();
    }

    public function isActive()
    {
        return $this->isActive;
    }

    public function deactivate()
    {
        $this->isActive = false;
    }

    public function activate()
    {
        $this->isActive = true;
    }

    public function update(string $name)
    {
        $this->name = $name;
        $this->validate();
    }

    private function validate()
    {
        DomainValidation::strMinLength($this->name);
        DomainValidation::strMaxLength($this->name);
    }

    public function addCategory(string $categoryId)
    {
        array_push($this->categoriesId, $categoryId);
    }

    public function removeCategory(string $categoryId)
    {
        unset($this->categoriesId[array_search($categoryId, $this->categoriesId)]);
    }
}
