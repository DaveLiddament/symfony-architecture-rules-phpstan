<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\DtoDeclarationRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<DtoDeclarationRule>
 */
final class DtoDeclarationRuleTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new DtoDeclarationRule(new RoleResolver([]));
    }

    #[Test]
    public function aFinalDtoIsAcceptedWithoutBeingReadonly(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/Dto/GoodDto.php');
    }

    #[Test]
    public function aDtoThatIsNotFinalIsReported(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/Dto/NotFinalDto.php');
    }

    #[Test]
    public function classesWithoutTheAttributeAreIgnored(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/Dto/PlainDto.php');
    }
}
