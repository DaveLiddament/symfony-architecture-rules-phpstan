<?php

declare(strict_types=1);

namespace DaveLiddament\SymfonyArchitectureRulesPhpstan\Rules\Roles;

use DaveLiddament\SymfonyArchitecture\Attribute\ConfigProvider;
use DaveLiddament\SymfonyArchitecture\Attribute\QueueGateway;
use DaveLiddament\SymfonyArchitecture\Attribute\Repository;
use DaveLiddament\SymfonyArchitecture\Attribute\Serializer;
use DaveLiddament\SymfonyArchitecture\Attribute\Service;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\ClassPropertyNode;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;

/**
 * A #[Service] holds only collaborators: classes carrying #[Service],
 * #[Repository], #[QueueGateway], #[Serializer] or #[ConfigProvider],
 * interfaces, or code from outside the app namespace (vendor, Lib), plus
 * iterables of these. No primitives (configuration arrives through a
 * #[ConfigProvider]), no entities, no DTOs.
 *
 * @implements Rule<ClassPropertyNode>
 */
final class ServiceDependencyRule implements Rule
{
    private const array COLLABORATOR_ATTRIBUTES = [
        Service::class,
        Repository::class,
        QueueGateway::class,
        Serializer::class,
        ConfigProvider::class,
    ];

    private string $appNamespace;

    public function __construct(string $appNamespace)
    {
        $this->appNamespace = trim($appNamespace, '\\');
    }

    #[\Override]
    public function getNodeType(): string
    {
        return ClassPropertyNode::class;
    }

    /**
     * @return list<IdentifierRuleError>
     */
    #[\Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $reflection = $node->getClassReflection();
        if (!RoleAttribute::isOn($reflection, Service::class) || !$reflection->hasNativeProperty($node->getName())) {
            return [];
        }

        $type = $reflection->getNativeProperty($node->getName())->getReadableType();
        if ($this->isAllowed($type)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf(
                'Service dependency %s::$%s must be a #[Service], #[Repository], #[QueueGateway], #[Serializer] or #[ConfigProvider] class, an interface, code from outside %s, or an iterable of these.',
                $reflection->getName(),
                $node->getName(),
                $this->appNamespace,
            ))
                ->identifier('service.dependencyType')
                ->build(),
        ];
    }

    private function isAllowed(Type $type): bool
    {
        if ($type instanceof UnionType) {
            foreach ($type->getTypes() as $inner) {
                if (!$this->isAllowed($inner)) {
                    return false;
                }
            }

            return true;
        }

        if ($type->isNull()->yes()) {
            return true;
        }

        if ($type->isArray()->yes()) {
            return $this->isAllowed($type->getIterableValueType());
        }

        $classReflections = $type->getObjectClassReflections();
        if ([] !== $classReflections) {
            foreach ($classReflections as $classReflection) {
                if (!$this->isCollaborator($classReflection)) {
                    return false;
                }
            }

            return true;
        }

        // The bare "iterable" native type (tagged-service collections).
        if ($type->isIterable()->yes()) {
            return $this->isAllowed($type->getIterableValueType());
        }

        return false;
    }

    private function isCollaborator(ClassReflection $classReflection): bool
    {
        if ($classReflection->isInterface() || !str_starts_with($classReflection->getName(), $this->appNamespace.'\\')) {
            return true;
        }

        foreach (self::COLLABORATOR_ATTRIBUTES as $attribute) {
            if (RoleAttribute::isOn($classReflection, $attribute)) {
                return true;
            }
        }

        return false;
    }
}
