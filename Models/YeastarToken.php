<?php

namespace Coverterror\YeastarForLaravel\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property string $access_token
 * @property Carbon|null $access_token_expire_time
 * @property string $refresh_token
 * @property Carbon|null $refresh_token_expire_time
 * @property string $token
 * @mixin Builder
 */
class YeastarToken extends Model
{
    protected $table = 'yeastar_tokens';

    protected $fillable = [
        'access_token',
        'access_token_expire_time',
        'refresh_token',
        'refresh_token_expire_time',
    ];

}