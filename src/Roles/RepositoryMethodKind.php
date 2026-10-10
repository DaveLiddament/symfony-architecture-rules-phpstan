<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Roles;

/**
 * The fixed vocabulary of public repository methods. Each value is the
 * prefix a method name starts with.
 */
enum RepositoryMethodKind: string
{
    case Find = 'find';
    case Get = 'get';
    case Persist = 'persist';
    case Update = 'update';
    case Delete = 'delete';
    case Has = 'has';
    case Is = 'is';

    /**
     * The kind a method name belongs to, or null if it is outside the
     * vocabulary. The prefix must end the name or be followed by an
     * uppercase letter or digit, so "getaway" is not a get.
     */
    public static function fromMethodName(string $name): ?self
    {
        foreach (self::cases() as $kind) {
            if (!str_starts_with($name, $kind->value)) {
                continue;
            }

            $rest = substr($name, strlen($kind->value));
            if ('' === $rest || 1 === preg_match('/^[A-Z0-9]/', $rest)) {
                return $kind;
            }
        }

        return null;
    }

    /**
     * Whether a method with this name only reads. A name outside the
     * vocabulary is not a read: it may change state.
     */
    public static function isReadMethodName(string $name): bool
    {
        return self::fromMethodName($name)?->isRead() ?? false;
    }

    public function isRead(): bool
    {
        return match ($this) {
            self::Find, self::Get, self::Has, self::Is => true,
            self::Persist, self::Update, self::Delete => false,
        };
    }
}
