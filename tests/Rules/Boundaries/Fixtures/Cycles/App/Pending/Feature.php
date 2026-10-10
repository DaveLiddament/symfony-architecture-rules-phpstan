<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Cycles\App\Pending;

final class Feature
{
    public function usesInvoice(\DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Cycles\App\Billing\Invoice $value): void
    {
    }
}
