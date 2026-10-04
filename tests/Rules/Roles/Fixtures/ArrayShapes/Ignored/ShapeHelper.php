<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\ArrayShapes\Ignored;

final readonly class ShapeHelper
{
    /**
     * @return array{name: string}
     */
    public function shape(): array
    {
        return ['name' => 'x'];
    }
}
