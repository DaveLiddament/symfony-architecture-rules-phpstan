<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Cycles\App\Billing;

final class Invoice
{
    public function usesReport(\DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Cycles\App\Stats\Report $value): void // ERROR Domains "Billing", "Pending", "Stats" depend on each other in a cycle: Billing → Stats → Pending → Billing (DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Cycles\App\Billing\Invoice uses DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Cycles\App\Stats\Report; DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Cycles\App\Stats\Report uses DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Cycles\App\Pending\Feature; DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Cycles\App\Pending\Feature uses DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\Cycles\App\Billing\Invoice).
    {
    }
}
