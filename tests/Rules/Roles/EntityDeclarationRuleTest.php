<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\EntityDeclarationRule;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\Entity\FakeOrm\OrmEntity;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<EntityDeclarationRule>
 */
final class EntityDeclarationRuleTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new EntityDeclarationRule(new RoleResolver(['entity' => ['attributes' => [OrmEntity::class]]]));
    }

    #[Test]
    public function aFinalEntityIsAcceptedWithoutBeingReadonly(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/Entity/FinalEntity.php');
    }

    #[Test]
    public function anEntityMarkedFinalInPhpDocIsAccepted(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/Entity/PhpDocFinalEntity.php');
    }

    #[Test]
    public function anEntityThatIsNotFinalIsReported(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/Entity/NotFinalEntity.php');
    }

    #[Test]
    public function anEntityRecognisedByAnAliasIsChecked(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/Entity/NotFinalOrmEntity.php');
    }

    #[Test]
    public function classesWithoutTheRoleAreIgnored(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/Entity/PlainEntity.php');
    }
}
