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

    /**
     * Pending code, which acts as a domain with the given name.
     */
    public static function pending(string $name): self
    {
        return new self(AreaType::Pending, $name);
    }

    public function is(AreaType $type): bool
    {
        return $type === $this->type;
    }

    public function isAppCode(): bool
    {
        return in_array($this->type, [AreaType::AppRoot, AreaType::Domain, AreaType::Shared, AreaType::Pending], true);
    }

    /**
     * Whether the area acts as a domain: a domain, or Pending.
     */
    public function actsAsDomain(): bool
    {
        return $this->is(AreaType::Domain) || $this->is(AreaType::Pending);
    }
}
