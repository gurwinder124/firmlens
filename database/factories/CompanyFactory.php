<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_name' => $this->faker->Company(),
            'company_type' => $this->faker->text(5),
            'request_status' =>rand(1,2,3),
            'created_at'=> date('Y-m-d H:i:s',rand(1662100000,1662113343))
        ];
    }
}
