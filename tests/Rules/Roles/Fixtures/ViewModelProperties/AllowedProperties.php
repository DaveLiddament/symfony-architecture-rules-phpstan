<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ViewModelProperties;

use DaveLiddament\Architecture\Attribute\ViewModel;

#[ViewModel]
final readonly class AllowedProperties
{
    /**
     * @param list<string> $lines
     * @param list<InnerViewModel> $rows
     */
    public function __construct(
        public int $count,
        public ?string $subtitle,
        public float $total,
        public bool $highlighted,
        public \DateTimeImmutable $when,
        public Status $status,
        public InnerViewModel $header,
        public array $lines,
        public array $rows,
    ) {
    }
}
