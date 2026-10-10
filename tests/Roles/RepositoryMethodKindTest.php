<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Roles;

use DaveLiddament\PhpstanArchitectureRules\Roles\RepositoryMethodKind;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class RepositoryMethodKindTest extends TestCase
{
    /**
     * @return iterable<string, array{string, RepositoryMethodKind|null, bool}>
     */
    public static function methodNameProvider(): iterable
    {
        yield 'find' => ['findById', RepositoryMethodKind::Find, true];
        yield 'get' => ['getAll', RepositoryMethodKind::Get, true];
        yield 'has' => ['hasEmail', RepositoryMethodKind::Has, true];
        yield 'is' => ['isTaken', RepositoryMethodKind::Is, true];
        yield 'persist' => ['persist', RepositoryMethodKind::Persist, false];
        yield 'update' => ['updateName', RepositoryMethodKind::Update, false];
        yield 'delete' => ['delete2', RepositoryMethodKind::Delete, false];
        yield 'not at a camelCase boundary' => ['getaway', null, false];
        yield 'outside the vocabulary' => ['save', null, false];
    }

    #[Test]
    #[DataProvider('methodNameProvider')]
    public function classifiesMethodNames(string $name, ?RepositoryMethodKind $expected, bool $isRead): void
    {
        self::assertSame($expected, RepositoryMethodKind::fromMethodName($name));
        self::assertSame($isRead, RepositoryMethodKind::isReadMethodName($name));
    }
}
