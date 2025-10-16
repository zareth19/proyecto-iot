<?php

namespace Database\Factories;

use App\Models\SensorData;
use Illuminate\Database\Eloquent\Factories\Factory;

class SensorDataFactory extends Factory
{
    protected $model = SensorData::class;

    public function definition()
    {
        return [
            'ph' => $this->faker->randomFloat(1, 6.0, 8.0),
            'temperatura' => $this->faker->numberBetween(20, 30),
            'turbidez' => $this->faker->numberBetween(10, 40),
            // agrega otros campos si existen en tu tabla
        ];
    }
}
