<?php
declare(strict_types=1);


namespace Flames\Ready;

/**
 * @internal
 */
class ResetData
{
    public static array $data = [
        '.env'        => [],
        '.env.public' => [],
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