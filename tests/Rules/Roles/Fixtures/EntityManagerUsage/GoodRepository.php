<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\EntityManagerUsage;

use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\EntityManagerUsage\FakeDoctrine\EntityManagerInterface;
use DaveLiddament\SymfonyArchitecture\Attribute\Repository;

#[Repository]
final readonly class GoodRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }
}
