<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Build\CheckRuleHasTest\Fixtures\FakeSrc;

use PhpParser\Node;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

/** @implements Rule<Name> */
final class UntestedRule implements Rule // ERROR Rule DaveLiddament\PhpstanArchitectureRules\Tests\Build\CheckRuleHasTest\Fixtures\FakeSrc\UntestedRule has no test: expected UntestedRuleTest.php somewhere in the tests directory.
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
