<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\ValueObjectPropertyTypeRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<ValueObjectPropertyTypeRule>
 */
final class ValueObjectPropertyTypeRuleTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new ValueObjectPropertyTypeRule(new RoleResolver([]));
    }

    #[\Override]
    protected function getErrorFormatter(): string
    {
        return 'Value object property {0} must be a primitive, \DateTimeImmutable, an enum, another value object, or a list of these.';
    }

    #[Test]
    public function primitivesDatesEnumsValueObjectsAndListsOfThemAreAllowed(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/Fixtures/ValueObjectProperties/AllowedProperties.php',
            __DIR__.'/Fixtures/ValueObjectProperties/Currency.php',
            __DIR__.'/Fixtures/ValueObjectProperties/Status.php',
        );
    }

    #[Test]
    public function disallowedPropertyTypesAreReported(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/ValueObjectProperties/BadProperties.php');
    }

    #[Test]
    public function propertiesOfClassesWithoutTheAttributeAreIgnored(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/ValueObjectProperties/NoAttribute.php');
    }
}
