<?php

declare(strict_types=1);

namespace Doctrine\ORM;

use Doctrine\Persistence\ObjectManager;

/**
 * Stand-in for the Doctrine interface, so the Doctrine preset can be tested without installing Doctrine.
 */
interface EntityManagerInterface extends ObjectManager
{
}
