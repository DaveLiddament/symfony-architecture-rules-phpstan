<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Boundaries;

use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Boundaries\BoundaryClassifier;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Boundaries\LibIsolationRule;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<LibIsolationRule>
 */
final class LibIsolationRuleTest extends AbstractRuleTestCase
{
    private ?BoundaryClassifier $classifier = null;

    #[\Override]
    protected function getRule(): Rule
    {
        return new LibIsolationRule($this->classifier ?? BoundaryFixtures::classifier());
    }

    #[Test]
    public function allowedReferencesReportNoErrors(): void
    {
        $this->assertIssuesReported(...BoundaryFixtures::allowedFiles());
    }

    #[Test]
    public function libCannotDependOnAppCode(): void
    {
        $this->assertIssuesReported(BoundaryFixtures::file('Lib/BadUtil.php'));
    }

    #[Test]
    public function nothingIsReportedWhenLibIsNull(): void
    {
        $this->classifier = BoundaryFixtures::classifier(libNamespace: null);

        $this->analyse([BoundaryFixtures::file('Lib/BadUtil.php')], []);
    }
}
