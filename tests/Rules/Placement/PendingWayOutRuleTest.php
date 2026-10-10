<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement;

use DaveLiddament\PhpstanArchitectureRules\Rules\Placement\PendingWayOutRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<PendingWayOutRule>
 */
final class PendingWayOutRuleTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new PendingWayOutRule();
    }

    #[\Override]
    protected function getCollectors(): array
    {
        return PlacementFixtures::collectors('PendingApp');
    }

    #[Test]
    public function pendingClassesWithOnlyOneDomainInvolvedAreReported(): void
    {
        $this->assertIssuesReported(...PlacementFixtures::files('PendingApp'));
    }
}
