<?php

namespace Database\Factories;

use App\Models\UserCity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserCityFactory extends Factory
{
    protected $model = UserCity::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'city' => $this->faker->city,
            'is_favorite' => false,
            'send_forecast' => false,
        ];
    }
}