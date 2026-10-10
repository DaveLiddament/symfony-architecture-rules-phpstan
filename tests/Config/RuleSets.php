<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Tests\Config;

use DaveLiddament\PhpstanArchitectureRules\Rules\Boundaries\DomainInternalRule;
use DaveLiddament\PhpstanArchitectureRules\Rules\Boundaries\LibIsolationRule;
use DaveLiddament\PhpstanArchitectureRules\Rules\Boundaries\NurseryIsolationRule;
use DaveLiddament\PhpstanArchitectureRules\Rules\Boundaries\RoleLocationRule;
use DaveLiddament\PhpstanArchitectureRules\Rules\Boundaries\SharedIsolationRule;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\ArrayShapeReturnRule;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\CliCommandDeclarationRule;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\ConfigProviderDeclarationRule;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\ConfigProviderPropertyTypeRule;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\ConfigProviderUsageRule;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\ControllerMethodReturnTypeRule;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\DtoDeclarationRule;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\EntityDeclarationRule;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\EntityManagerUsageRule;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\FormTypeDeclarationRule;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\QueueProcessorDeclarationRule;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\RepositoryDeclarationRule;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\RepositoryDependencyRule;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\RepositoryMethodRule;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\RoleRequiredRule;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\SerializerDeclarationRule;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\ServiceDeclarationRule;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\ServiceDependencyRule;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\ValueObjectDeclarationRule;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\ValueObjectPropertyTypeRule;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\ViewModelDeclarationRule;
use DaveLiddament\PhpstanArchitectureRules\Rules\Roles\ViewModelPropertyTypeRule;
use PHPStan\DependencyInjection\Container;

/**
 * The rules in each config group, sorted by class name.
 */
final class RuleSets
{
    public const array BOUNDARIES = [
        DomainInternalRule::class,
        LibIsolationRule::class,
        NurseryIsolationRule::class,
        RoleLocationRule::class,
        SharedIsolationRule::class,
    ];

    public const array ROLE_REQUIRED = [
        RoleRequiredRule::class,
    ];

    /**
     * The rules turned off by each role's switch.
     */
    public const array ROLES = [
        'cliCommand' => [
            CliCommandDeclarationRule::class,
        ],
        'configProvider' => [
            ConfigProviderDeclarationRule::class,
            ConfigProviderPropertyTypeRule::class,
            ConfigProviderUsageRule::class,
        ],
        'controller' => [
            ControllerMethodReturnTypeRule::class,
        ],
        'dto' => [
            DtoDeclarationRule::class,
        ],
        'entity' => [
            EntityDeclarationRule::class,
        ],
        'formType' => [
            FormTypeDeclarationRule::class,
        ],
        'queueProcessor' => [
            QueueProcessorDeclarationRule::class,
        ],
        'repository' => [
            EntityManagerUsageRule::class,
            RepositoryDeclarationRule::class,
            RepositoryDependencyRule::class,
            RepositoryMethodRule::class,
        ],
        'serializer' => [
            ArrayShapeReturnRule::class,
            SerializerDeclarationRule::class,
        ],
        'service' => [
            ServiceDeclarationRule::class,
            ServiceDependencyRule::class,
        ],
        'valueObject' => [
            ValueObjectDeclarationRule::class,
            ValueObjectPropertyTypeRule::class,
        ],
        'viewModel' => [
            ViewModelDeclarationRule::class,
            ViewModelPropertyTypeRule::class,
        ],
    ];

    /**
     * All the role rules, sorted by class name.
     *
     * @param list<string> $exceptRoles
     *
     * @return list<string>
     */
    public static function roles(array $exceptRoles = []): array
    {
        $rules = [];
        foreach (self::ROLES as $role => $roleRules) {
            if (!in_array($role, $exceptRoles, true)) {
                $rules = [...$rules, ...$roleRules];
            }
        }
        sort($rules);

        return $rules;
    }

    /**
     * The rules from $ruleSet that PHPStan will run with the container's config.
     *
     * @param list<string> $ruleSet
     *
     * @return list<string>
     */
    public static function enabledIn(Container $container, array $ruleSet): array
    {
        $enabled = [];
        foreach ($container->getServicesByTag('phpstan.rules.rule') as $rule) {
            if (is_object($rule) && in_array($rule::class, $ruleSet, true)) {
                $enabled[] = $rule::class;
            }
        }
        sort($enabled);

        return $enabled;
    }
}
