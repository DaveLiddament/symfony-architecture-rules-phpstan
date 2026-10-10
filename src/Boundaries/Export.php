<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanArchitectureRules\Boundaries;

use DaveLiddament\Architecture\Attribute\Exported;
use PHPStan\Reflection\ClassReflection;

/**
 * Which other domains may use a domain class, read from its #[Exported]
 * attribute. The attribute counts only on the class that declares it, not
 * on its subclasses.
 */
final readonly class Export
{
    /**
     * @param list<string>|null $to the domains that may use the class, or null for every domain
     */
    private function __construct(
        public ?array $to,
    ) {
    }

    /**
     * The class's export, or null if it is not exported.
     */
    public static function of(ClassReflection $classReflection): ?self
    {
        foreach ($classReflection->getAttributes() as $attribute) {
            if (Exported::class !== $attribute->getName()) {
                continue;
            }

            $to = $attribute->getArgumentTypes()['to'] ?? null;
            if (null === $to || $to->isNull()->yes()) {
                return new self(null);
            }

            $domains = [];
            foreach ($to->getConstantArrays() as $array) {
                foreach ($array->getValueTypes() as $value) {
                    foreach ($value->getConstantStrings() as $domain) {
                        $domains[] = $domain->getValue();
                    }
                }
            }

            return new self(array_values(array_unique($domains)));
        }

        return null;
    }

    /**
     * Whether code in the given domain may use the class. Null means code
     * outside any domain (e.g. the Nursery), which may only use classes
     * exported to every domain.
     */
    public function allows(?string $domain): bool
    {
        return null === $this->to || in_array($domain, $this->to, true);
    }

    /**
     * The domains in `to`, quoted for an error message.
     */
    public function describeTo(): string
    {
        if (null === $this->to) {
            return 'every domain';
        }

        if ([] === $this->to) {
            return 'no domains';
        }

        return implode(', ', array_map(static fn (string $domain): string => '"'.$domain.'"', $this->to));
    }
}
