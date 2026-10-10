<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ConfigProviderProperties;

use DaveLiddament\SymfonyArchitecture\Attribute\ConfigProvider;

#[ConfigProvider]
final readonly class AllowedProperties
{
    /**
     * @param list<string> $hosts
     */
    public function __construct(
        public string $host,
        public ?string $label,
        public int $port,
        public float $ratio,
        public bool $enabled,
        public array $hosts,
    ) {
    }
}
