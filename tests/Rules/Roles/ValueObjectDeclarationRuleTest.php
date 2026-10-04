<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles;

use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\ValueObjectDeclarationRule;
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
        return new ValueObjectDeclarationRule();
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
