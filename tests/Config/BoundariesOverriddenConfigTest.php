<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Config;

use DaveLiddament\PhpstanArchitectureRules\Boundaries\Area;
use DaveLiddament\PhpstanArchitectureRules\Boundaries\AreaType;
use DaveLiddament\PhpstanArchitectureRules\Boundaries\BoundaryClassifier;
use PHPStan\Testing\PHPStanTestCase;
use PHPUnit\Framework\Attributes\Test;

final class BoundariesOverriddenConfigTest extends PHPStanTestCase
{
    #[\Override]
    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__.'/../../extension.neon',
            __DIR__.'/Fixtures/boundaries-overridden.neon',
        ];
    }

    #[Test]
    public function rulesStayEnabled(): void
    {
        self::assertSame(RuleSets::BOUNDARIES, RuleSets::enabledIn(self::getContainer(), RuleSets::BOUNDARIES));
    }

    #[Test]
    public function overriddenNamespacesAreUsed(): void
    {
        $classifier = self::getContainer()->getByType(BoundaryClassifier::class);

        self::assertEquals(Area::of(AreaType::Shared), $classifier->classifyClass('Common\User'));
        self::assertEquals(Area::domain('Billing'), $classifier->classifyClass('Acme\Billing\Invoice'));
        self::assertEquals(Area::of(AreaType::Ignored), $classifier->classifyClass('Acme\Tests\SomeTest'));
    }

    #[Test]
    public function nullNamespacesTurnAreasOff(): void
    {
        $classifier = self::getContainer()->getByType(BoundaryClassifier::class);

        self::assertEquals(Area::of(AreaType::External), $classifier->classifyClass('Lib\Clock'));
        self::assertEquals(Area::domain('Pending'), $classifier->classifyClass('Acme\Pending\NewThing'));
    }

    #[Test]
    public function ignoredNamespacesAreReplacedNotMerged(): void
    {
        $classifier = self::getContainer()->getByType(BoundaryClassifier::class);

        self::assertEquals(Area::of(AreaType::External), $classifier->classifyClass('App\Tests\SomeTest'));
    }
}
