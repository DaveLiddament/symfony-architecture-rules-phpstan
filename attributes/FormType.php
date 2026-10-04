<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitecture\Attribute;

/**
 * A Symfony form type. Must be final; it extends AbstractType, so it cannot be readonly.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class FormType
{
}
