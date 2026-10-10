<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Boundaries;

use DaveLiddament\PhpstanArchitectureRules\Boundaries\Area;
use DaveLiddament\PhpstanArchitectureRules\Boundaries\AreaType;
use DaveLiddament\PhpstanArchitectureRules\Boundaries\BoundaryClassifier;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class BoundaryClassifierTest extends TestCase
{
    /**
     * @return iterable<string, array{string, Area}>
     */
    public static function defaultClassesProvider(): iterable
    {
        yield 'lib' => ['Lib\Clock', Area::of(AreaType::Lib)];
        yield 'lib sub-namespace' => ['Lib\Time\Clock', Area::of(AreaType::Lib)];
        yield 'shared' => ['App\Shared\User', Area::of(AreaType::Shared)];
        yield 'shared sub-namespace' => ['App\Shared\Entity\User', Area::of(AreaType::Shared)];
        yield 'nursery' => ['App\Nursery\NewThing', Area::of(AreaType::Nursery)];
        yield 'nursery sub-namespace' => ['App\Nursery\Controller\NewController', Area::of(AreaType::Nursery)];
        yield 'app root' => ['App\Kernel', Area::of(AreaType::AppRoot)];
        yield 'domain' => ['App\Registration\RegistrationService', Area::domain('Registration')];
        yield 'domain sub-namespace' => ['App\Registration\Entity\User', Area::domain('Registration')];
        yield 'domain deep sub-namespace' => ['App\Registration\Entity\Sub\User', Area::domain('Registration')];
        yield 'ignored' => ['App\Tests\SomeTest', Area::of(AreaType::Ignored)];
        yield 'ignored sub-namespace' => ['App\Tests\Registration\SomeTest', Area::of(AreaType::Ignored)];
        yield 'vendor' => ['Symfony\Component\HttpFoundation\Response', Area::of(AreaType::External)];
        yield 'global namespace' => ['DateTimeImmutable', Area::of(AreaType::External)];
        yield 'prefix of app is not app' => ['Application\Foo\Bar', Area::of(AreaType::External)];
        yield 'prefix of shared is a domain' => ['App\SharedThings\Foo', Area::domain('SharedThings')];
    }

    #[Test]
    #[DataProvider('defaultClassesProvider')]
    public function classifiesClassesUsingTheDefaultNamespaces(string $className, Area $expected): void
    {
        self::assertEquals($expected, $this->defaultClassifier()->classifyClass($className));
    }

    #[Test]
    public function classifiesNamespaces(): void
    {
        $classifier = $this->defaultClassifier();

        self::assertEquals(Area::of(AreaType::AppRoot), $classifier->classifyNamespace('App'));
        self::assertEquals(Area::domain('Registration'), $classifier->classifyNamespace('App\Registration'));
        self::assertEquals(Area::domain('Registration'), $classifier->classifyNamespace('App\Registration\Entity'));
        self::assertEquals(Area::of(AreaType::External), $classifier->classifyNamespace(null));
        self::assertEquals(Area::of(AreaType::External), $classifier->classifyNamespace(''));
    }

    #[Test]
    public function nullNamespacesTurnAreasIntoOrdinaryCode(): void
    {
        $classifier = new BoundaryClassifier('App', null, null, null, []);

        self::assertEquals(Area::of(AreaType::External), $classifier->classifyClass('Lib\Clock'));
        self::assertEquals(Area::domain('Shared'), $classifier->classifyClass('App\Shared\User'));
        self::assertEquals(Area::domain('Nursery'), $classifier->classifyClass('App\Nursery\NewThing'));
        self::assertEquals(Area::domain('Tests'), $classifier->classifyClass('App\Tests\SomeTest'));
    }

    #[Test]
    public function sharedAndNurseryCanLiveOutsideTheAppNamespace(): void
    {
        $classifier = new BoundaryClassifier('Acme\App', 'Acme\Lib', 'Acme\Shared', 'Acme\Nursery', []);

        self::assertEquals(Area::of(AreaType::Shared), $classifier->classifyClass('Acme\Shared\User'));
        self::assertEquals(Area::of(AreaType::Nursery), $classifier->classifyClass('Acme\Nursery\NewThing'));
        self::assertEquals(Area::of(AreaType::Lib), $classifier->classifyClass('Acme\Lib\Clock'));
        self::assertEquals(Area::domain('Billing'), $classifier->classifyClass('Acme\App\Billing\Invoice'));
        self::assertEquals(Area::of(AreaType::External), $classifier->classifyClass('App\Shared\User'));
    }

    #[Test]
    public function leadingAndTrailingBackslashesAreIgnored(): void
    {
        $classifier = new BoundaryClassifier('\App\\', '\Lib\\', '\App\Shared\\', '', ['\App\Tests\\']);

        self::assertEquals(Area::of(AreaType::Lib), $classifier->classifyClass('Lib\Clock'));
        self::assertEquals(Area::of(AreaType::Shared), $classifier->classifyClass('App\Shared\User'));
        self::assertEquals(Area::of(AreaType::Ignored), $classifier->classifyClass('App\Tests\SomeTest'));
        self::assertEquals(Area::domain('Nursery'), $classifier->classifyClass('App\Nursery\NewThing'), 'An empty namespace means no nursery');
    }

    #[Test]
    public function givesTheNamespaceEachAreaIsRootedAt(): void
    {
        $classifier = new BoundaryClassifier('App', 'Lib', 'Common', 'App\Nursery', ['App\Tests']);

        self::assertSame('App\Registration', $classifier->getAreaNamespace(Area::domain('Registration')));
        self::assertSame('Common', $classifier->getAreaNamespace(Area::of(AreaType::Shared)));
        self::assertSame('App\Nursery', $classifier->getAreaNamespace(Area::of(AreaType::Nursery)));
        self::assertNull($classifier->getAreaNamespace(Area::of(AreaType::Lib)));
        self::assertNull($classifier->getAreaNamespace(Area::of(AreaType::AppRoot)));
    }

    private function defaultClassifier(): BoundaryClassifier
    {
        return new BoundaryClassifier('App', 'Lib', 'App\Shared', 'App\Nursery', ['App\Tests']);
    }
}
