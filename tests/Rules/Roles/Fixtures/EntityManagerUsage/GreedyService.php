<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\EntityManagerUsage;

use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\EntityManagerUsage\FakeDoctrine\EntityManagerInterface;
use DaveLiddament\Architecture\Attribute\Service;

#[Service]
final readonly class GreedyService
{
    public function __construct(
        private EntityManagerInterface $entityManager, // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\EntityManagerUsage\GreedyService::$entityManager
    ) {
    }
}
