<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Exported\App\Walks\Exportable;

use DaveLiddament\Architecture\Attribute\Exported;
use DaveLiddament\Architecture\Attribute\Service;

#[Exported]
#[Service]
final readonly class WalkPlanner
{
    public function __construct(
        private WalkRepository $walkRepository,
    ) {
    }

    public function plan(Walk $walk): void
    {
        $this->walkRepository->persistWalk($walk);
        $this->walkRepository->save($walk);
    }
}
