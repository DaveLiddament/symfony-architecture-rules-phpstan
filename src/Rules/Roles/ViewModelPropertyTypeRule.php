<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Rules\Roles;

use DaveLiddament\Architecture\Attribute\ViewModel;

/**
 * Properties of a #[ViewModel] must be render-ready values: primitives,
 * \DateTimeImmutable, enums, other view models, or lists of these. Unlike
 * a value object, a view model may not hold value objects: it renders
 * formatted values rather than carrying domain values.
 */
final class ViewModelPropertyTypeRule extends AbstractValuePropertyTypeRule
{
    #[\Override]
    protected function getAttributeClass(): string
    {
        return ViewModel::class;
    }

    #[\Override]
    protected function getMessage(): string
    {
        return 'View model property %s must be a primitive, \DateTimeImmutable, an enum, another view model, or a list of these.';
    }

    #[\Override]
    protected function getIdentifier(): string
    {
        return 'viewModel.propertyType';
    }
}
