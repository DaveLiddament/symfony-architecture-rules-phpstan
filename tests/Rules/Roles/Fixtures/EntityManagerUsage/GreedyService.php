<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\EntityManagerUsage;

use DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\EntityManagerUsage\FakeDoctrine\EntityManagerInterface;
use DaveLiddament\SymfonyArchitecture\Attribute\Service;

#[Service]
final readonly class GreedyService
{
    public function __construct(
        private EntityManagerInterface $entityManager, // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\EntityManagerUsage\GreedyService::$entityManager
    ) {
    }
}
