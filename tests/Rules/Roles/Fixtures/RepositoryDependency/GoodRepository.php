<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\RepositoryDependency;

use DaveLiddament\Architecture\Attribute\Repository;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\RepositoryDependency\FakeDoctrine\EntityManager;

#[Repository]
final readonly class GoodRepository
{
    /**
     * @param list<Walk> $walks
     * @param list<Distance|Walk>|null $mixed
     */
    public function __construct(
        private EntityManager $entityManager,
        private ?EntityManager $maybeManager,
        private OtherRepository $otherRepository,
        private array $walks,
        private ?array $mixed,
    ) {
    }
}
