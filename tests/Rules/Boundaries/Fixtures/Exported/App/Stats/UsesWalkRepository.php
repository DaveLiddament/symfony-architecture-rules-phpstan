<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Exported\App\Stats;

use DaveLiddament\Architecture\Attribute\Service;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Exported\App\Walks\Exportable\Walk;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Exported\App\Walks\Exportable\WalkPlanner;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Exported\App\Walks\Exportable\WalkRepository;

#[Service]
final readonly class UsesWalkRepository
{
    public function __construct(
        private WalkRepository $walkRepository,
        private WalkPlanner $walkPlanner,
    ) {
    }

    public function run(Walk $walk): void
    {
        $this->walkRepository->findWalk(1);
        $this->walkRepository->getWalks();
        $this->walkRepository->hasWalk(1);
        $this->walkRepository->isEmpty();
        $this->walkPlanner->plan($walk);
        $this->walkRepository->persistWalk($walk); // ERROR Repository method DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Exported\App\Walks\Exportable\WalkRepository::persistWalk() is not a read, so it cannot be called from outside domain "Walks": other domains may only call find*, get*, has* and is* methods.
        $this->walkRepository->save($walk); // ERROR Repository method DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Exported\App\Walks\Exportable\WalkRepository::save() is not a read, so it cannot be called from outside domain "Walks": other domains may only call find*, get*, has* and is* methods.
    }
}
