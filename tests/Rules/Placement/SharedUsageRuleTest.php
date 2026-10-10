<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Placement;

use DaveLiddament\PhpstanArchitectureRules\Rules\Placement\SharedUsageRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<SharedUsageRule>
 */
final class SharedUsageRuleTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new SharedUsageRule();
    }

    #[\Override]
    protected function getCollectors(): array
    {
        return PlacementFixtures::collectors('SharedApp');
    }

    #[Test]
    public function sharedClassesUsedByOneDomainOrNoneAreReported(): void
    {
        $this->assertIssuesReported(...PlacementFixtures::files('SharedApp'));
    }
}
