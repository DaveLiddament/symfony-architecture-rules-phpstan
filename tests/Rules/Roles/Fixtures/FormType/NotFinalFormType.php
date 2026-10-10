<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\FormType;

use Symfony\Component\Form\AbstractType;

class NotFinalFormType extends AbstractType // ERROR Form type DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\FormType\NotFinalFormType must be final.
{
}
