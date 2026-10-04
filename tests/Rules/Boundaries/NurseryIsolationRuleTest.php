<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries;

use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Boundaries\BoundaryClassifier;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Boundaries\NurseryIsolationRule;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<NurseryIsolationRule>
 */
final class NurseryIsolationRuleTest extends AbstractRuleTestCase
{
    private ?BoundaryClassifier $classifier = null;

    #[\Override]
    protected function getRule(): Rule
    {
        return new NurseryIsolationRule($this->classifier ?? BoundaryFixtures::classifier());
    }

    #[Test]
    public function allowedReferencesReportNoErrors(): void
    {
        $this->assertIssuesReported(...BoundaryFixtures::allowedFiles());
    }

    #[Test]
    public function domainRootCannotDependOnTheNursery(): void
    {
        $this->assertIssuesReported(BoundaryFixtures::file('App/Gym/UsesNursery.php'));
    }

    #[Test]
    public function domainInternalsCannotDependOnTheNursery(): void
    {
        $this->assertIssuesReported(BoundaryFixtures::file('App/Gym/Repository/UsesNurseryFromInternal.php'));
    }

    #[Test]
    public function nothingIsReportedWhenNurseryIsNull(): void
    {
        $this->classifier = BoundaryFixtures::classifier(nurseryNamespace: null);

        $this->analyse([
            BoundaryFixtures::file('App/Gym/UsesNursery.php'),
            BoundaryFixtures::file('App/Gym/Repository/UsesNurseryFromInternal.php'),
        ], []);
    }
}
