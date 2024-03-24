<?php

namespace Coverterror\YeastarForLaravel\Database\Factories;

use Coverterror\YeastarForLaravel\Models\YeastarToken;
use Illuminate\Database\Eloquent\Factories\Factory;

class YeastarTokenFactory extends Factory
{
    protected $model = YeastarToken::class;

    public function definition()
    {
        return [
            'access_token' => $this->faker->sha256,
            'access_token_expire_time' => now()->addSecond(),
            'refresh_token' => $this->faker->sha256,
            'refresh_token_expire_time' =>  now()->addSecond(),
        ];
    }
}