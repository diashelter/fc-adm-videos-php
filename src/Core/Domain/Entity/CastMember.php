<?php

declare(strict_types=1);

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MethodsMagicsTrait;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\ValueObject\Uuid;
use DateTimeImmutable;
use DateTimeInterface;

class CastMember
{
    use MethodsMagicsTrait;

    private function __construct(
        protected ?Uuid $id,
        protected string $name,
        protected CastMemberType $type,
        protected ?DateTimeInterface $createdAt,
    ) {
        $this->validate();
    }

    public static function create(string $name, string $type): CastMember
    {
        return new CastMember(
            id: Uuid::random(),
            name: $name,
            type: CastMemberType::from((int) $type),
            createdAt: new DateTimeImmutable(),
        );
    }

    public static function restore(string $id, string $name, string $type, string $createdAt): CastMember
    {
        return new CastMember(
            id: new Uuid($id),
            name: $name,
            type: CastMemberType::from((int) $type),
            createdAt: new DateTimeImmutable($createdAt),
        );
    }

    public function update(string $name)
    {
        $this->name = $name;

        $this->validate();
    }

    protected function validate(): void
    {
        DomainValidation::strMaxLength($this->name);
        DomainValidation::strMinLength($this->name);
    }
}
