<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Build\CheckRuleHasTest\Fixtures\FakeSrc;

use PhpParser\Node;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

/** @implements Rule<Name> */
final class FixturelessRule implements Rule // ERROR Rule DaveLiddament\PhpstanArchitectureRules\Tests\Build\CheckRuleHasTest\Fixtures\FakeSrc\FixturelessRule has no fixtures: expected at least one file in Fixtureless/Fixtures.
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
