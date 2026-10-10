<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\ConfigProviderUsageRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<ConfigProviderUsageRule>
 */
final class ConfigProviderUsageRuleTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new ConfigProviderUsageRule(new RoleResolver([]));
    }

    #[\Override]
    protected function getErrorFormatter(): string
    {
        return 'Config providers may only be injected into #[Service] classes, but {0} holds one.';
    }

    #[Test]
    public function aServiceMayHoldAConfigProvider(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/Fixtures/ConfigProviderUsage/GoodService.php',
            __DIR__.'/Fixtures/ConfigProviderUsage/TheConfig.php',
        );
    }

    #[Test]
    public function anythingElseHoldingAConfigProviderIsReported(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/Fixtures/ConfigProviderUsage/PlainHolder.php',
            __DIR__.'/Fixtures/ConfigProviderUsage/HoardingRepository.php',
        );
    }

    #[Test]
    public function classesNotInvolvingConfigProvidersAreIgnored(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/Fixtures/ConfigProviderUsage/Unrelated.php',
            __DIR__.'/Fixtures/ConfigProviderUsage/SelfIterating.php',
        );
    }
}
