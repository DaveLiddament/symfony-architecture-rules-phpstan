<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\ConfigProviderPropertyTypeRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<ConfigProviderPropertyTypeRule>
 */
final class ConfigProviderPropertyTypeRuleTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new ConfigProviderPropertyTypeRule(new RoleResolver([]));
    }

    #[\Override]
    protected function getErrorFormatter(): string
    {
        return 'Config provider property {0} must be a scalar or a list of scalars.';
    }

    #[Test]
    public function scalarsAndListsOfScalarsAreAllowed(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/ConfigProviderProperties/AllowedProperties.php');
    }

    #[Test]
    public function everythingElseIncludingEnumsAndDatesIsReported(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/Fixtures/ConfigProviderProperties/BadProperties.php',
            __DIR__.'/Fixtures/ConfigProviderProperties/Level.php',
        );
    }

    #[Test]
    public function propertiesOfClassesWithoutTheAttributeAreIgnored(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/ConfigProviderProperties/NoAttribute.php');
    }
}
