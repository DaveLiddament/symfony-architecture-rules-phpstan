<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\RepositoryDependency;

use DaveLiddament\SymfonyArchitecture\Attribute\Repository;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\RepositoryDependency\FakeDoctrine\EntityManager;

#[Repository]
final readonly class GoodRepository
{
    public function __construct(
        private EntityManager $entityManager,
        private ?EntityManager $maybeManager,
    ) {
    }
}
