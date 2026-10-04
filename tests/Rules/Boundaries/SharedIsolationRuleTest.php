<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries;

use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Boundaries\BoundaryClassifier;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Boundaries\SharedIsolationRule;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<SharedIsolationRule>
 */
final class SharedIsolationRuleTest extends AbstractRuleTestCase
{
    private ?BoundaryClassifier $classifier = null;

    #[\Override]
    protected function getRule(): Rule
    {
        return new SharedIsolationRule($this->classifier ?? BoundaryFixtures::classifier());
    }

    #[Test]
    public function allowedReferencesReportNoErrors(): void
    {
        $this->assertIssuesReported(...BoundaryFixtures::allowedFiles());
    }

    #[Test]
    public function sharedCannotDependOnDomainsOrTheNursery(): void
    {
        $this->assertIssuesReported(BoundaryFixtures::file('App/Shared/BadShared.php'));
    }

    #[Test]
    public function nothingIsReportedWhenSharedIsNull(): void
    {
        $this->classifier = BoundaryFixtures::classifier(sharedNamespace: null);

        $this->analyse([BoundaryFixtures::file('App/Shared/BadShared.php')], []);
    }
}
