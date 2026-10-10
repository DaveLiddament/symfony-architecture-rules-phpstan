<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Build\CheckRuleIsInExtension\Fixtures;

use PhpParser\Node;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

/** @implements Rule<Name> */
final class UntaggedRule implements Rule // ERROR Rule [DaveLiddament\PhpstanArchitectureRules\Tests\Build\CheckRuleIsInExtension\Fixtures\UntaggedRule] not in extension.neon.
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
