<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\PersistenceUsage;

use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\PersistenceUsage\FakeDoctrine\EntityManagerInterface;
use DaveLiddament\Architecture\Attribute\Service;

#[Service]
final readonly class GreedyService
{
    public function __construct(
        private EntityManagerInterface $entityManager, // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\PersistenceUsage\GreedyService::$entityManager|DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\PersistenceUsage\FakeDoctrine\EntityManagerInterface
    ) {
    }
}
