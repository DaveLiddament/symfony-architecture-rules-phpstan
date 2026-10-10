<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement;

use DaveLiddament\PhpstanArchitectureRules\Rules\Placement\UnusedExportRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<UnusedExportRule>
 */
final class UnusedExportRuleTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new UnusedExportRule();
    }

    #[\Override]
    protected function getCollectors(): array
    {
        return PlacementFixtures::collectors('ExportApp');
    }

    #[Test]
    public function exportsThatListedDomainsDoNotUseAreReported(): void
    {
        $this->assertIssuesReported(...PlacementFixtures::files('ExportApp'));
    }
}
