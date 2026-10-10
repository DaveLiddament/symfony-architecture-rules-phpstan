<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries;

use DaveLiddament\PhpstanArchitectureRules\Rules\Boundaries\DomainInternalRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
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
        return new DomainInternalRule(BoundaryFixtures::classifier(), $this->createReflectionProvider());
    }

    #[Test]
    public function allowedReferencesReportNoErrors(): void
    {
        $this->assertIssuesReported(...BoundaryFixtures::allowedFiles());
    }

    #[Test]
    public function classesNotExportedToADomainCannotBeUsedFromIt(): void
    {
        $this->assertIssuesReported(BoundaryFixtures::file('App/Gym/UsesHomeInternal.php'));
    }

    #[Test]
    public function theNurseryCanOnlyUseClassesExportedToEveryDomain(): void
    {
        $this->assertIssuesReported(BoundaryFixtures::file('App/Nursery/UsesDomainInternal.php'));
    }

    #[Test]
    public function sharedIsLeftToSharedIsolationRule(): void
    {
        $this->analyse([BoundaryFixtures::file('App/Shared/BadShared.php')], []);
    }
}
