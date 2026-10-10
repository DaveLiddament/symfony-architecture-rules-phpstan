<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\SerializerDeclarationRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<SerializerDeclarationRule>
 */
final class SerializerDeclarationRuleTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new SerializerDeclarationRule(new RoleResolver([]));
    }

    #[Test]
    public function aFinalSerializerIsAcceptedWithoutBeingReadonly(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/Serializer/GoodSerializer.php');
    }

    #[Test]
    public function aSerializerThatIsNotFinalIsReported(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/Serializer/NotFinalSerializer.php');
    }

    #[Test]
    public function classesWithoutTheAttributeAreIgnored(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/Serializer/PlainSerializer.php');
    }
}
