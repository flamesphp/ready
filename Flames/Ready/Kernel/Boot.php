<?php

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
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        (new DumpDecoratorsRich())->setAssetsNeeded(true);
        (new DumpDecoratorsPlain())->setAssetsNeeded(true);
    }

    protected static function simulateError(): void
    {
//        echo 'tudo certo';
//        dump('teste 00001');

//        throw new \Exception('falhooooou');
    }

    private static function onRequest(): void
    {
        ob_start();
        try {
            Dispatch::dispatch();
            self::simulateError();
            ob_end_flush();
        } catch (\Throwable $e) {
            ob_end_clean();
            try {
                $handler = FrameworkBoot::$errorHandler;
                if ($handler !== null) {
                    $handler->handleException($e);
                } else {
                    self::fallbackError($e);
                }
            } catch (\Throwable $handlerError) {
                self::fallbackError($e, $handlerError);
            }
        }
    }

    private static function fallbackError(\Throwable $e, ?\Throwable $handlerError = null): void
    {
//        http_response_code(500);
//        header('Content-Type: text/plain; charset=UTF-8');
//        echo get_class($e) . ': ' . $e->getMessage() . "\n";
//        echo $e->getFile() . ':' . $e->getLine() . "\n\n";
//        echo $e->getTraceAsString();
//        if ($handlerError !== null) {
//            echo "\n\n--- Error handler failed ---\n";
//            echo get_class($handlerError) . ': ' . $handlerError->getMessage() . "\n";
//            echo $handlerError->getFile() . ':' . $handlerError->getLine();
//        }
    }
}
