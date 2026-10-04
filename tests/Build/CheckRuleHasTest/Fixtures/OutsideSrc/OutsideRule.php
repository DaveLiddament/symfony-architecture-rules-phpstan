<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Build\CheckRuleHasTest\Fixtures\OutsideSrc;

use PhpParser\Node;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

/** @implements Rule<Name> */
final class OutsideRule implements Rule 
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
