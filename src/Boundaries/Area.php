<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Boundaries;

/**
 * The architectural area a namespace belongs to.
 */
final readonly class Area
{
    private function __construct(
        public AreaType $type,
        public ?string $domain,
    ) {
    }

    public static function of(AreaType $type): self
    {
        return new self($type, null);
    }

    public static function domain(string $name): self
    {
        return new self(AreaType::Domain, $name);
    }

    public function is(AreaType $type): bool
    {
        return $type === $this->type;
    }

    public function isAppCode(): bool
    {
        return in_array($this->type, [AreaType::AppRoot, AreaType::Domain, AreaType::Shared, AreaType::Nursery], true);
    }
}
