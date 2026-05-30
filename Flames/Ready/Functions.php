<?php

declare(strict_types=1);

namespace Flames\Ready;

use Flames\Framework\PersistentData;

/**
 * @internal
 */
final class Functions
{
    public static function once(\Closure $function): mixed
    {
        if (PersistentData::$weakMap->offsetExists($function)) {
            return PersistentData::$weakMap[$function];
        }

        PersistentData::$weakMap[$function] = $function();
        return PersistentData::$weakMap[$function];
    }
}