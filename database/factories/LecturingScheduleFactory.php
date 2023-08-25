<?php

namespace Database\Factories;

use App\User;
use App\Models\LecturingSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class LecturingScheduleFactory extends Factory
{
    protected $model = LecturingSchedule::class;

    public function definition()
    {
        return [
            'title'       => $this->faker->word,
            'description' => $this->faker->sentence,
            'creator_id'  => function () {
                return User::factory()->create()->id;
            },
        ];
    }
}
