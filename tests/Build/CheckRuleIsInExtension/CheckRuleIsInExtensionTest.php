<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Build\CheckRuleIsInExtension;

use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Build\PHPStan\Rules\CheckRuleIsInExtension;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<CheckRuleIsInExtension>
 */
final class CheckRuleIsInExtensionTest extends AbstractRuleTestCase
{
    private const string FIXTURES_NAMESPACE = 'DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Build\CheckRuleIsInExtension\Fixtures';

    #[\Override]
    protected function getRule(): Rule
    {
        return new CheckRuleIsInExtension(
            __DIR__.'/Fixtures/extension.neon',
            self::FIXTURES_NAMESPACE,
            [self::FIXTURES_NAMESPACE.'\Excluded'],
        );
    }

    #[Test]
    public function rulesTaggedDirectlyAreRegistered(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/RegisteredRule.php');
    }

    #[Test]
    public function rulesTaggedViaConditionalTagsAreRegistered(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/ConditionallyRegisteredRule.php');
    }

    #[Test]
    public function aServiceWithoutTheRuleTagIsReported(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/UntaggedRule.php');
    }

    #[Test]
    public function aRuleMissingFromTheExtensionIsReported(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/UnregisteredRule.php');
    }

    #[Test]
    public function abstractRulesNonRulesAndExcludedNamespacesAreIgnored(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/Fixtures/AbstractRule.php',
            __DIR__.'/Fixtures/NotARule.php',
            __DIR__.'/Fixtures/Excluded/ExcludedRule.php',
        );
    }
}
