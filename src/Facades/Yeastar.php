<?php

namespace Coverterror\YeastarForLaravel\Facades;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Coverterror\YeastarForLaravel\Yeastar
 * @method static mixed makeCall(string $caller, string $callee, bool $returnOnlyStatus = true)
 * /
 */
class Yeastar extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Coverterror\YeastarForLaravel\Yeastar::class;
    }
}
