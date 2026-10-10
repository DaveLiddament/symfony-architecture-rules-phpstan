<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries;

use DaveLiddament\PhpstanArchitectureRules\Boundaries\BoundaryClassifier;
use DaveLiddament\PhpstanArchitectureRules\Roles\RoleResolver;
use DaveLiddament\PhpstanArchitectureRules\Rules\Boundaries\RoleLocationRule;
use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\RoleLocation\FakeOrm\Entity;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<RoleLocationRule>
 */
final class RoleLocationRuleTest extends AbstractRuleTestCase
{
    private const string FIXTURES = 'DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Boundaries\Fixtures\RoleLocation';

    #[\Override]
    protected function getRule(): Rule
    {
        $classifier = new BoundaryClassifier(
            self::FIXTURES.'\App',
            self::FIXTURES.'\Lib',
            self::FIXTURES.'\App\Shared',
            self::FIXTURES.'\App\Nursery',
            [self::FIXTURES.'\App\Tests'],
        );

        return new RoleLocationRule($classifier, new RoleResolver(['entity' => ['attributes' => [Entity::class]]]));
    }

    #[\Override]
    protected function getErrorFormatter(): string
    {
        return '{0} has the {1} role, so it must live in {2}.';
    }

    #[Test]
    public function rolesInTheirRoleDirectoryAreAccepted(): void
    {
        $this->assertIssuesReported(
            $this->fixture('App/Gym/Controller/GymController.php'),
            $this->fixture('App/Gym/CliCommand/GymCommand.php'),
            $this->fixture('App/Gym/Entity/Member.php'),
            $this->fixture('App/Gym/Repository/MemberRepository.php'),
            $this->fixture('App/Shared/Entity/User.php'),
            $this->fixture('App/Shared/Repository/UserRepository.php'),
            $this->fixture('App/Nursery/Controller/NewController.php'),
        );
    }

    #[Test]
    public function aRepositoryMayLiveAtTheAreaRoot(): void
    {
        $this->assertIssuesReported($this->fixture('App/Gym/GymRepository.php'));
    }

    #[Test]
    public function entryPointsAndEntitiesCannotLiveAtTheAreaRoot(): void
    {
        $this->assertIssuesReported(
            $this->fixture('App/Gym/BadController.php'),
            $this->fixture('App/Gym/RootEntity.php'),
            $this->fixture('App/Shared/SharedEntity.php'),
            $this->fixture('App/Nursery/NurseryController.php'),
        );
    }

    #[Test]
    public function rolesInTheWrongDirectoryAreReported(): void
    {
        $this->assertIssuesReported(
            $this->fixture('App/Gym/Service/MisplacedCommand.php'),
            $this->fixture('App/Gym/Service/MisplacedRepository.php'),
        );
    }

    #[Test]
    public function otherRolesMayLiveAnywhere(): void
    {
        $this->assertIssuesReported(
            $this->fixture('App/Gym/GymService.php'),
            $this->fixture('App/Gym/Repository/FreeRangeService.php'),
            $this->fixture('App/Gym/Service/AnywhereDto.php'),
        );
    }

    #[Test]
    public function libAndIgnoredCodeAreNotChecked(): void
    {
        $this->assertIssuesReported(
            $this->fixture('Lib/LibCommand.php'),
            $this->fixture('App/Tests/IgnoredController.php'),
        );
    }

    private function fixture(string $path): string
    {
        return __DIR__.'/Fixtures/RoleLocation/'.$path;
    }
}
