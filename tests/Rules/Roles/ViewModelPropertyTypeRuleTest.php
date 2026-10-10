<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\ViewModelPropertyTypeRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<ViewModelPropertyTypeRule>
 */
final class ViewModelPropertyTypeRuleTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new ViewModelPropertyTypeRule();
    }

    #[\Override]
    protected function getErrorFormatter(): string
    {
        return 'View model property {0} must be a primitive, \DateTimeImmutable, an enum, another view model, or a list of these.';
    }

    #[Test]
    public function primitivesDatesEnumsViewModelsAndListsOfThemAreAllowed(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/Fixtures/ViewModelProperties/AllowedProperties.php',
            __DIR__.'/Fixtures/ViewModelProperties/InnerViewModel.php',
            __DIR__.'/Fixtures/ViewModelProperties/Status.php',
        );
    }

    #[Test]
    public function disallowedPropertyTypesIncludingValueObjectsAreReported(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/ViewModelProperties/BadProperties.php');
    }

    #[Test]
    public function propertiesOfClassesWithoutTheAttributeAreIgnored(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/ViewModelProperties/NoAttribute.php');
    }
}
