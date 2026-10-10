<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Build\CheckRuleHasTest\Fixtures\FakeSrc;

use PhpParser\Node;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

/** @implements Rule<Name> */
final class EmptyFixturesRule implements Rule // ERROR Rule DaveLiddament\PhpstanArchitectureRules\Tests\Build\CheckRuleHasTest\Fixtures\FakeSrc\EmptyFixturesRule has no fixtures: expected at least one file in EmptyFixtures/Fixtures.
{
    public function getNodeType(): string
    {
        return Name::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        return [];
    }
}
