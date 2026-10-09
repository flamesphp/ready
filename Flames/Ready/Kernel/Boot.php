<?php
declare(strict_types=1);


namespace Flames\Ready\Kernel;

use Flames\Dumpper\Decorators\DumpDecoratorsRich;
use Flames\Dumpper\Decorators\DumpDecoratorsPlain;
use Flames\Env\Env;
use Flames\Framework\Boot as FrameworkBoot;
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
        Register::reset(self::class, 'onRequestReset');

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
        FrameworkBoot::registerWebHandlers();

        if (FrameworkBoot::$errorHandler !== null) {
            FrameworkBoot::$errorHandler->allowQuit(false);
        }
    }

    public static function onRequestReset(): void
    {
        if (FrameworkBoot::$errorHandler !== null) {
            FrameworkBoot::$errorHandler->writeToOutput(true);
        }

        new DumpDecoratorsRich()->setAssetsNeeded(true);
        new DumpDecoratorsPlain()->setAssetsNeeded(true);
    }

    protected static function simulateError(): void
    {
//        echo 'tudo certo';
//        dump('teste 0000');

//        throw new \Exception('falhooooou');
    }

    private static function onRequest(): void
    {
        try {
            Dispatch::dispatch();
            self::simulateError();
        } catch (\Throwable $e) {
            FrameworkBoot::renderException($e);
        }
    }
}
