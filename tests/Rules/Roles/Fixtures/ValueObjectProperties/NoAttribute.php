<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\ValueObjectProperties;

final class NoAttribute
{
    public \stdClass $anything;

    public \DateTime $mutable;
}
