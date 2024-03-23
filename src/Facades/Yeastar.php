<?php

namespace Coverterror\YeastarForLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Coverterror\YeastarForLaravel\Yeastar
 */
class Yeastar extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Coverterror\YeastarForLaravel\Yeastar::class;
    }
}
