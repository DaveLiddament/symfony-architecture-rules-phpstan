<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries;

use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Boundaries\DomainInternalRule;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<DomainInternalRule>
 */
final class DomainInternalRuleTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new DomainInternalRule(BoundaryFixtures::classifier());
    }

    #[Test]
    public function allowedReferencesReportNoErrors(): void
    {
        $this->assertIssuesReported(...BoundaryFixtures::allowedFiles());
    }

    #[Test]
    public function domainInternalsCannotBeUsedFromAnotherDomain(): void
    {
        $this->assertIssuesReported(BoundaryFixtures::file('App/Gym/UsesHomeInternal.php'));
    }

    #[Test]
    public function domainInternalsCannotBeUsedFromTheNursery(): void
    {
        $this->assertIssuesReported(BoundaryFixtures::file('App/Nursery/UsesDomainInternal.php'));
    }

    #[Test]
    public function sharedIsLeftToSharedIsolationRule(): void
    {
        $this->analyse([BoundaryFixtures::file('App/Shared/BadShared.php')], []);
    }
}
