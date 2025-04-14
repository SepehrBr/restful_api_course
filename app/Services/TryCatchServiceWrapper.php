<?php

namespace App\Services;

use Closure;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Throwable;

use function PHPUnit\Framework\isNull;

class TryCatchServiceWrapper
{
    public function __invoke(Closure $action, ?Closure $reject = null)
    {
        try {
            $actionResult = $action();

            return new ResultService(true, $actionResult);
        } catch (Throwable $th) {
            ! is_null($reject) && $reject();

            app()[ExceptionHandler::class]->report($th);

            return new ResultService(false, $th->getMessage());
        }
    }
}
