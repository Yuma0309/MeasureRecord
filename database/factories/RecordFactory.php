<?php

namespace Database\Factories;

use App\Models\Record;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Record>
 */
class RecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => 1, //1
            'title_id' => $this->faker->numberBetween(17, 18), //17~18
            'date' => $this->faker->dateTimeBetween($startDate = 'now', $endDate = '+1 week'), //現在から1週間後までYmd
            'amount' => $this->faker->numberBetween(190.5, 220.3), //190.5~220.7
            'comment' => $this->faker->word(), //文字列
            'created_at' => $this->faker->dateTime('now'), //現在までYmdHis
            'updated_at' => $this->faker->dateTime('now'), //現在までYmdHis
        ];
    }
}
