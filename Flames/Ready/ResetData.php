<?php

namespace Flames\Ready;

/**
 * @internal
 */
class ResetData
{
    public static array $data = [
        '.env' => []
    ];

    public static \WeakMap $weakMap;

    public static ?array $freezeData = null;

    public static function clean()
    {
        self::$weakMap = new \WeakMap();
        self::$data = self::$freezeData;
    }
}

ResetData::$weakMap = new \WeakMap();
ResetData::$freezeData = ResetData::$data;