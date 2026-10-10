<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\RepositoryMethods;

use DaveLiddament\SymfonyArchitecture\Attribute\Repository;

#[Repository]
final class GoodRepository
{
    public function __construct(
        private readonly string $constructorIsExempt = '',
    ) {
    }

    public function findFor(int $id): ?Thing
    {
        return null;
    }

    public function findFirst(): ?Thing
    {
        return null;
    }

    /**
     * @return list<Thing>
     */
    public function getAllFor(): array
    {
        return [];
    }

    public function getCount(): int
    {
        return 0;
    }

    public function persist(Thing $thing): void
    {
    }

    public function persistOther(Thing $thing): void
    {
    }

    public function update(): void
    {
    }

    public function deleteOlderThan(\DateTimeImmutable $cutoff): void
    {
    }

    public function hasAny(): bool
    {
        return false;
    }

    public function isEmpty(): bool
    {
        return true;
    }

    private function helperNamesAreFree(Thing $thing): string
    {
        return $this->constructorIsExempt;
    }
}
