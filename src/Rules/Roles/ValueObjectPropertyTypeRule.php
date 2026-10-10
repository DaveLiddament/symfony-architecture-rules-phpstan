<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Roles;

use DaveLiddament\SymfonyArchitecture\Attribute\ValueObject;

/**
 * Properties of a #[ValueObject] must themselves be values: primitives,
 * \DateTimeImmutable, enums, other value objects, or lists of these.
 * Entities, services, mutable dates and non-list arrays disqualify a class
 * from being a value.
 */
final class ValueObjectPropertyTypeRule extends AbstractValuePropertyTypeRule
{
    #[\Override]
    protected function getAttributeClass(): string
    {
        return ValueObject::class;
    }

    #[\Override]
    protected function getMessage(): string
    {
        return 'Value object property %s must be a primitive, \DateTimeImmutable, an enum, another value object, or a list of these.';
    }

    #[\Override]
    protected function getIdentifier(): string
    {
        return 'valueObject.propertyType';
    }
}
