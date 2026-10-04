<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\EntityManagerUsage;

use DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\EntityManagerUsage\FakeDoctrine\EntityManagerInterface;

final class PlainHolder
{
    public ?EntityManagerInterface $entityManager; // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\EntityManagerUsage\PlainHolder::$entityManager

    public FakeDoctrine\EntityManager $concrete; // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\EntityManagerUsage\PlainHolder::$concrete

    /** @var list<EntityManagerInterface> */
    public array $managers; // ERROR DaveLiddament\SymfonyArchitectureRulesPhpstan\Tests\Rules\Roles\Fixtures\EntityManagerUsage\PlainHolder::$managers

    public string $harmless;
}
