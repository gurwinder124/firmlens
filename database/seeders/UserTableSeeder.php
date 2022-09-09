<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()

    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'demo',
            'email' => 'demo@yopmail.com',
            'password' => Hash::make('mind@123'),
            'designation' => 'developer',
            'company_id' => '1',
            'is_root_user' => '1',
            'parent_id' => '1',
            'created_at' => date('Y-m-d H:i:s', rand(1662100000, 1662113343))
        ]);
        User::factory()->count(50)->create();
    }
}
