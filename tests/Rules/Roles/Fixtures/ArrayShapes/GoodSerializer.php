<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ArrayShapes;

use DaveLiddament\Architecture\Attribute\Serializer;

#[Serializer]
final readonly class GoodSerializer
{
    /**
     * @return array{name: string, count: int}
     */
    public function toWire(): array
    {
        return ['name' => 'x', 'count' => 1];
    }
}
