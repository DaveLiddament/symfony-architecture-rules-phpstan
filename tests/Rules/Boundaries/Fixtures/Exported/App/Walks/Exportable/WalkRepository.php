<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Exported\App\Walks\Exportable;

use DaveLiddament\Architecture\Attribute\Exported;
use DaveLiddament\Architecture\Attribute\Repository;

#[Exported]
#[Repository]
final readonly class WalkRepository
{
    public function findWalk(int $id): ?Walk
    {
        return null;
    }

    /** @return list<Walk> */
    public function getWalks(): array
    {
        return [];
    }

    public function hasWalk(int $id): bool
    {
        return false;
    }

    public function isEmpty(): bool
    {
        return true;
    }

    public function persistWalk(Walk $walk): void
    {
    }

    public function save(Walk $walk): void
    {
    }

    public function updateAll(): void
    {
    }
}
