<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ValueObjectProperties;

use DaveLiddament\SymfonyArchitecture\Attribute\ValueObject;

#[ValueObject]
final readonly class AllowedProperties
{
    /**
     * @param list<string> $tags
     * @param list<Currency> $currencies
     */
    public function __construct(
        public int $count,
        public ?string $label,
        public float $amount,
        public bool $flag,
        public \DateTimeImmutable $when,
        public Status $status,
        public Currency $currency,
        public array $tags,
        public array $currencies,
    ) {
    }
}
