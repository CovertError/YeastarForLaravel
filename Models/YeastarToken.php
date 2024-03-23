<?php

namespace Coverterror\YeastarForLaravel\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
/**
 * @mixin Builder
 */
class YeastarToken extends Model
{
    protected $table = 'yeastar_tokens'; // Specify the table name if it's not the pluralized form of the model name

    protected $fillable = [
        'access_token',
        'access_token_expire_time',
        'refresh_token',
        'refresh_token_expire_time',
    ];

}