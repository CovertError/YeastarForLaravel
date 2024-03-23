<?php

namespace Coverterror\YeastarForLaravel\Facades;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Coverterror\YeastarForLaravel\Yeastar
 */
/**
 * @property string $access_token
 * @property Carbon|null $access_token_expire_time
 * @property string $refresh_token
 * @property Carbon|null $refresh_token_expire_time
 * @property string $token
 */
class Yeastar extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Coverterror\YeastarForLaravel\Yeastar::class;
    }
}
