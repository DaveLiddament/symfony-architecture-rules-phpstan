<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ArrayShapes;

use DaveLiddament\SymfonyArchitecture\Attribute\Service;

#[Service]
final readonly class Shapely
{
    /**
     * @return array{name: string}
     */
    public function shape(): array // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ArrayShapes\Shapely::shape
    {
        return ['name' => 'x'];
    }

    /**
     * @return array{name: string}|null
     */
    public function nullableShape(): ?array // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ArrayShapes\Shapely::nullableShape
    {
        return null;
    }

    /**
     * @return list<array{name: string}>
     */
    public function listOfShapes(): array // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ArrayShapes\Shapely::listOfShapes
    {
        return [];
    }
}
