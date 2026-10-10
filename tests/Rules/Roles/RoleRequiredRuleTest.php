<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Boundaries\BoundaryClassifier;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\RoleRequiredRule;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\RoleRequired\FakeApp\Gym\ExemptGlue;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\RoleRequired\FakeOrm\Entity;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<RoleRequiredRule>
 */
final class RoleRequiredRuleTest extends AbstractRuleTestCase
{
    private const string FAKE_APP = 'DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\RoleRequired\FakeApp';

    #[\Override]
    protected function getRule(): Rule
    {
        $classifier = new BoundaryClassifier(
            self::FAKE_APP,
            null,
            self::FAKE_APP.'\Shared',
            self::FAKE_APP.'\Nursery',
            [self::FAKE_APP.'\Tests'],
        );

        return new RoleRequiredRule($classifier, Entity::class, [ExemptGlue::class]);
    }

    #[\Override]
    protected function getErrorFormatter(): string
    {
        return 'Class {0} declares no role: give it a role attribute from DaveLiddament\SymfonyArchitecture\Attribute (#[Service], #[Dto], #[ValueObject], ...).';
    }

    #[Test]
    public function attributedClassesEntitiesEnumsInterfacesAndTraitsAreAccepted(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/Fixtures/RoleRequired/FakeApp/Gym/AttributedService.php',
            __DIR__.'/Fixtures/RoleRequired/FakeApp/Gym/AnEntity.php',
            __DIR__.'/Fixtures/RoleRequired/FakeApp/Gym/SomeEnum.php',
            __DIR__.'/Fixtures/RoleRequired/FakeApp/Gym/SomeInterface.php',
            __DIR__.'/Fixtures/RoleRequired/FakeApp/Gym/SomeTrait.php',
        );
    }

    #[Test]
    public function rolelessClassesInDomainsSharedAndTheNurseryAreReported(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/Fixtures/RoleRequired/FakeApp/Gym/Naked.php',
            __DIR__.'/Fixtures/RoleRequired/FakeApp/Gym/Internal/NakedInternal.php',
            __DIR__.'/Fixtures/RoleRequired/FakeApp/Shared/NakedShared.php',
            __DIR__.'/Fixtures/RoleRequired/FakeApp/Nursery/NakedNursery.php',
        );
    }

    #[Test]
    public function exemptClassesFrameworkGlueIgnoredAndOutsideCodeMayStayRoleless(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/Fixtures/RoleRequired/FakeApp/Gym/ExemptGlue.php',
            __DIR__.'/Fixtures/RoleRequired/FakeApp/Kernel.php',
            __DIR__.'/Fixtures/RoleRequired/FakeApp/Tests/NakedTestHelper.php',
            __DIR__.'/Fixtures/RoleRequired/Outside.php',
        );
    }
}
