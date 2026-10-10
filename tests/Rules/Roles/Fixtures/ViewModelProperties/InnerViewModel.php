<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ViewModelProperties;

use DaveLiddament\SymfonyArchitecture\Attribute\ViewModel;

#[ViewModel]
final readonly class InnerViewModel
{
    public function __construct(
        public string $label,
    ) {
    }
}
