<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Frameworks;

use DaveLiddament\PhpstanArchitectureRules\Roles\Role;

/**
 * A framework preset: the role aliases and defaults that framework's
 * classes need. Each value is the framework's key in the
 * architecture.frameworks config.
 *
 * A preset only recognises classes that use the framework (its attributes,
 * base classes and interfaces), so it does nothing in a project that
 * doesn't use it.
 */
enum Framework: string
{
    case Doctrine = 'doctrine';
    case Symfony = 'symfony';

    /**
     * @param array<string, bool> $switches framework => enabled
     *
     * @return list<self>
     */
    public static function enabledIn(array $switches): array
    {
        return array_values(array_filter(
            self::cases(),
            static fn (self $framework): bool => $switches[$framework->value] ?? false,
        ));
    }

    /**
     * @return array<string, array{attributes?: list<string>, extends?: list<string>, implements?: list<string>}> role => aliases
     */
    public function roleAliases(): array
    {
        return match ($this) {
            self::Doctrine => [
                Role::Entity->value => [
                    'attributes' => ['Doctrine\ORM\Mapping\Entity'],
                ],
            ],
            self::Symfony => [
                Role::CliCommand->value => [
                    'attributes' => ['Symfony\Component\Console\Attribute\AsCommand'],
                    'extends' => ['Symfony\Component\Console\Command\Command'],
                ],
                Role::Controller->value => [
                    'attributes' => ['Symfony\Component\HttpKernel\Attribute\AsController'],
                    'extends' => ['Symfony\Bundle\FrameworkBundle\Controller\AbstractController'],
                ],
                Role::FormType->value => [
                    'extends' => ['Symfony\Component\Form\AbstractType'],
                ],
                Role::QueueProcessor->value => [
                    'attributes' => ['Symfony\Component\Messenger\Attribute\AsMessageHandler'],
                ],
            ],
        };
    }

    /**
     * The classes that talk to storage, which only a repository may hold.
     *
     * @return list<string>
     */
    public function persistenceClasses(): array
    {
        return match ($this) {
            self::Doctrine => [
                'Doctrine\DBAL\Connection',
                'Doctrine\ORM\EntityManagerInterface',
                'Doctrine\Persistence\ManagerRegistry',
                'Doctrine\Persistence\ObjectManager',
            ],
            self::Symfony => [],
        };
    }

    /**
     * The types public controller methods may return when the project
     * doesn't configure its own.
     *
     * @return list<string>
     */
    public function controllerReturnTypes(): array
    {
        return match ($this) {
            self::Doctrine => [],
            self::Symfony => ['Symfony\Component\HttpFoundation\Response'],
        };
    }
}
