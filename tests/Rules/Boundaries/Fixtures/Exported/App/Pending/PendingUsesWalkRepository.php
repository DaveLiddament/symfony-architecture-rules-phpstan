<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Exported\App\Pending;

use DaveLiddament\Architecture\Attribute\Service;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Exported\App\Walks\Exportable\WalkRepository;

#[Service]
final readonly class PendingUsesWalkRepository
{
    public function __construct(
        private WalkRepository $walkRepository,
    ) {
    }

    public function run(): void
    {
        $this->walkRepository->getWalks();
        $this->walkRepository->updateAll(); // ERROR Repository method DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Exported\App\Walks\Exportable\WalkRepository::updateAll() is not a read, so it cannot be called from outside domain "Walks": other domains may only call find*, get*, has* and is* methods.
    }
}
