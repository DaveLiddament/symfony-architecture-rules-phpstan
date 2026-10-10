<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ArrayShapes;

final readonly class Innocent
{
    /**
     * @return list<string>
     */
    public function plainList(): array
    {
        return [];
    }

    /**
     * @return array<string, int>
     */
    public function plainMap(): array
    {
        return [];
    }

    public function scalar(): string
    {
        return 'no shapes here';
    }

    /**
     * @return array{secret: string}
     */
    private function privateHelperMayShape(): array
    {
        return ['secret' => 'fine'];
    }
}
