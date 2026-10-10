<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ConfigProviderUsage;

use DaveLiddament\Architecture\Attribute\Repository;

#[Repository]
final class HoardingRepository
{
    public function __construct(
        public TheConfig $config, // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ConfigProviderUsage\HoardingRepository::$config
    ) {
    }
}
