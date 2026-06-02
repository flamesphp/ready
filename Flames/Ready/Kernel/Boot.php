<?php

namespace Flames\Ready\Kernel;

use Flames\Env\Env;
use Flames\Framework\Dispatch;
use Flames\Ready\Ready\Service\Register;

/**
 * @internal
 */
class Boot
{
    public static function boot(): void
    {
        if (!extension_loaded('cflames_ready_service')) {
            fwrite(STDERR, "[Flames Ready] Extension 'cflames_ready_service' is not loaded.\n");
            exit(1);
        }

        Register::load(self::class, 'onWorkerBoot');
        Register::reset(self::class, 'onWorkerReset');

        Register::request(static function (): void {
            self::onRequest();
        });
    }

    public static function onWorkerBoot(): void
    {
        if (!defined('FLAMES_READY_WORKER')) {
            define('FLAMES_READY_WORKER', true);
        }

        Env::reload();
    }

    public static function onWorkerReset(): void
    {
        // Reset per-request state between requests.
    }

    private static function onRequest(): void
    {
        Dispatch::dispatch();
    }
}
