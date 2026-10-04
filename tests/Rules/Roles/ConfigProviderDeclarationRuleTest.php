<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles;

use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\ConfigProviderDeclarationRule;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<ConfigProviderDeclarationRule>
 */
final class ConfigProviderDeclarationRuleTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new ConfigProviderDeclarationRule();
    }

    #[Test]
    public function aFinalReadonlyConfigProviderIsAccepted(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/ConfigProvider/GoodConfigProvider.php');
    }

    #[Test]
    public function aConfigProviderThatIsNotFinalIsReported(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/ConfigProvider/NotFinalConfigProvider.php');
    }

    #[Test]
    public function aConfigProviderThatIsNotReadonlyIsReported(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/ConfigProvider/NotReadonlyConfigProvider.php');
    }

    #[Test]
    public function classesWithoutTheAttributeAreIgnored(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/ConfigProvider/PlainConfigProvider.php');
    }
}
