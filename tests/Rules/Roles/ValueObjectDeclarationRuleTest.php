<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\ValueObjectDeclarationRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<ValueObjectDeclarationRule>
 */
final class ValueObjectDeclarationRuleTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new ValueObjectDeclarationRule(new RoleResolver([]));
    }

    #[Test]
    public function aFinalReadonlyValueObjectIsAccepted(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/ValueObject/GoodValueObject.php');
    }

    #[Test]
    public function aValueObjectThatIsNotFinalIsReported(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/ValueObject/NotFinalValueObject.php');
    }

    #[Test]
    public function aValueObjectThatIsNotReadonlyIsReported(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/ValueObject/NotReadonlyValueObject.php');
    }

    #[Test]
    public function classesWithoutTheAttributeAreIgnored(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/ValueObject/PlainValueObject.php');
    }
}
