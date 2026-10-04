<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles;

use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles\ArrayShapeReturnRule;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\Test;

/**
 * @extends AbstractRuleTestCase<ArrayShapeReturnRule>
 */
final class ArrayShapeReturnRuleTest extends AbstractRuleTestCase
{
    #[\Override]
    protected function getRule(): Rule
    {
        return new ArrayShapeReturnRule(['DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ArrayShapes\Ignored']);
    }

    #[\Override]
    protected function getErrorFormatter(): string
    {
        return 'Public method {0}() returns an array shape — make it a value object, DTO or view model, or move it into a #[Serializer].';
    }

    #[Test]
    public function aSerializerMayReturnArrayShapes(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/ArrayShapes/GoodSerializer.php');
    }

    #[Test]
    public function publicArrayShapeReturnsElsewhereAreReported(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/ArrayShapes/Shapely.php');
    }

    #[Test]
    public function privateHelpersAndShapelessReturnsAreFree(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/ArrayShapes/Innocent.php');
    }

    #[Test]
    public function ignoredNamespacesAreNotChecked(): void
    {
        $this->assertIssuesReported(__DIR__.'/Fixtures/ArrayShapes/Ignored/ShapeHelper.php');
    }
}
