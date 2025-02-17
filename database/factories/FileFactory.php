<?php

namespace factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'model_type' => ,
            'group' => $this->faker->randomElement(PageGroupEnum::cases()),
            'title' => $this->faker->realText(rand(10, 30)),
            'content' => $this->faker->realText(rand(300, 500)),
        ];
    }
}
