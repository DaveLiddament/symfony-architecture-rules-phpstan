<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\PersistenceUsage;

use DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\PersistenceUsage\FakeDoctrine\EntityManagerInterface;

final class PlainHolder
{
    public ?EntityManagerInterface $entityManager; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\PersistenceUsage\PlainHolder::$entityManager|DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\PersistenceUsage\FakeDoctrine\EntityManagerInterface

    public FakeDoctrine\EntityManager $concrete; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\PersistenceUsage\PlainHolder::$concrete|DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\PersistenceUsage\FakeDoctrine\EntityManagerInterface

    /** @var list<EntityManagerInterface> */
    public array $managers; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\PersistenceUsage\PlainHolder::$managers|DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\PersistenceUsage\FakeDoctrine\EntityManagerInterface

    public FakeDoctrine\Connection $connection; // ERROR DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\PersistenceUsage\PlainHolder::$connection|DaveLiddament\PhpstanArchitectureRules\Tests\Rules\Roles\Fixtures\PersistenceUsage\FakeDoctrine\Connection

    public string $harmless;
}
