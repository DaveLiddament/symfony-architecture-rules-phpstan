<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\EntityManagerUsage;

use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\EntityManagerUsage\FakeDoctrine\EntityManagerInterface;

final class PlainHolder
{
    public ?EntityManagerInterface $entityManager; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\EntityManagerUsage\PlainHolder::$entityManager

    public FakeDoctrine\EntityManager $concrete; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\EntityManagerUsage\PlainHolder::$concrete

    /** @var list<EntityManagerInterface> */
    public array $managers; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\EntityManagerUsage\PlainHolder::$managers

    public string $harmless;
}
