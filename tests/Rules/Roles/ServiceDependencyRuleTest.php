<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles;

use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\ServiceDependencyRule;
use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<ServiceDependencyRule>
 */
final class ServiceDependencyRuleTest extends AbstractRuleTestCase
{
    private const string FAKE_APP = 'DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ServiceDependency\FakeApp';

    #[\Override]
    protected function getRule(): Rule
    {
        return new ServiceDependencyRule(self::FAKE_APP);
    }

    #[\Override]
    protected function getErrorFormatter(): string
    {
        return 'Service dependency {0} must be a #[Service], #[Repository], #[QueueGateway], #[Serializer] or #[ConfigProvider] class, an interface, code from outside '.self::FAKE_APP.', or an iterable of these.';
    }

    #[Test]
    public function collaboratorsInterfacesVendorCodeAndIterablesOfThemAreAllowed(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/Fixtures/ServiceDependency/FakeApp/GoodService.php',
            __DIR__.'/Fixtures/ServiceDependency/FakeApp/OtherService.php',
            __DIR__.'/Fixtures/ServiceDependency/FakeApp/SomeRepository.php',
            __DIR__.'/Fixtures/ServiceDependency/FakeApp/SomeGateway.php',
            __DIR__.'/Fixtures/ServiceDependency/FakeApp/SomeSerializer.php',
            __DIR__.'/Fixtures/ServiceDependency/FakeApp/TheConfig.php',
            __DIR__.'/Fixtures/ServiceDependency/FakeApp/SomeInterface.php',
        );
    }

    #[Test]
    public function primitivesPlainClassesDtosAndUntypedDependenciesAreReported(): void
    {
        $this->assertIssuesReported(
            __DIR__.'/Fixtures/ServiceDependency/FakeApp/BadService.php',
            __DIR__.'/Fixtures/ServiceDependency/FakeApp/PlainCollaborator.php',
            __DIR__.'/Fixtures/ServiceDependency/FakeApp/SomeDto.php',
        );
    }

    #[Test]
    public function propertiesOfNonServiceClassesAreIgnored(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/ServiceDependency/FakeApp/NoAttribute.php');
    }
}
