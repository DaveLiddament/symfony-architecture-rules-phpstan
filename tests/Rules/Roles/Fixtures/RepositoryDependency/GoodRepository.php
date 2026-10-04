<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\RepositoryDependency;

use DaveLiddament\SymfonyArchitecture\Attribute\Repository;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\RepositoryDependency\FakeDoctrine\EntityManager;

#[Repository]
final readonly class GoodRepository
{
    public function __construct(
        private EntityManager $entityManager,
        private ?EntityManager $maybeManager,
    ) {
    }
}
