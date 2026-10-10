<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\PersistenceUsage;

use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\PersistenceUsage\FakeDoctrine\EntityManagerInterface;
use DaveLiddament\Architecture\Attribute\Repository;

#[Repository]
final readonly class GoodRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }
}
