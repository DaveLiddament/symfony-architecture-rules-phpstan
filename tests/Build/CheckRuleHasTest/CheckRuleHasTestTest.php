<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Build\CheckRuleHasTest;

use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Build\PHPStan\Rules\CheckRuleHasTest;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<CheckRuleHasTest>
 */
final class CheckRuleHasTestTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new CheckRuleHasTest([__DIR__.'/Fixtures/FakeSrc'], __DIR__.'/Fixtures/FakeTests');
    }

    #[Test]
    public function aRuleWithATestAndFixturesIsAccepted(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/FakeSrc/TestedRule.php');
    }

    #[Test]
    public function aRuleWithoutATestIsReported(): void
    {
        // FakeTests/Tested/Fixtures/UntestedRuleTest.php is a fixture, so it doesn't count.
        $this->assertIssuesReported(__DIR__.'/Fixtures/FakeSrc/UntestedRule.php');
    }

    #[Test]
    public function aRuleWithoutAFixturesDirectoryIsReported(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/FakeSrc/FixturelessRule.php');
    }

    #[Test]
    public function aRuleWithAFixturesDirectoryContainingNoFilesIsReported(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/FakeSrc/EmptyFixturesRule.php');
    }

    #[Test]
    public function abstractRulesNonRulesAndRulesOutsideTheRuleDirectoriesAreIgnored(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/Fixtures/FakeSrc/AbstractRule.php',
            __DIR__.'/Fixtures/FakeSrc/NotARule.php',
            __DIR__.'/Fixtures/OutsideSrc/OutsideRule.php',
        );
    }
}
