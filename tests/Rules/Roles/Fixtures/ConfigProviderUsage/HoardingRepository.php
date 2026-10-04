<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ConfigProviderUsage;

use DaveLiddament\SymfonyArchitecture\Attribute\Repository;

#[Repository]
final class HoardingRepository
{
    public function __construct(
        public TheConfig $config, // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ConfigProviderUsage\HoardingRepository::$config
    ) {
    }
}
