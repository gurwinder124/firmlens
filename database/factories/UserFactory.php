<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;


class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
   // protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'designation'=> $this->faker->text(5),
            'company_id'=> rand(1,10),
            'is_root_user'=>'0',
            'is_active'=>'1',
            'parent_id'=>rand(1,10),
            'password' => Hash::make('mind@123'), // password
            'created_at'=> date('Y-m-d H:i:s',rand(1662100000,1662113343))
        ];
    }
}
