<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ConfigProviderUsage;

/**
 * Iterates over itself, like Symfony's FormInterface.
 *
 * @implements \IteratorAggregate<int, SelfIterating>
 */
final class SelfIterating implements \IteratorAggregate
{
    public function getIterator(): \Iterator
    {
        return new \ArrayIterator([]);
    }
}
