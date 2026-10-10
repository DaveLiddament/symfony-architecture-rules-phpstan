<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Placement;

use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * A class in Shared, Pending or a domain, as collected by
 * PlacedClassCollector.
 */
final readonly class PlacedClass
{
    /**
     * @param 'shared'|'pending'|'domain' $area
     * @param list<string>|null $exportedTo
     */
    public function __construct(
        public string $name,
        public string $area,
        public ?string $domain,
        public bool $exported,
        public ?array $exportedTo,
        public string $file,
        public int $line,
    ) {
    }

    /**
     * An error reported at the class's declaration.
     */
    public function error(string $message, string $identifier): IdentifierRuleError
    {
        return RuleErrorBuilder::message($message)
            ->file($this->file)
            ->line($this->line)
            ->identifier($identifier)
            ->build();
    }
}
