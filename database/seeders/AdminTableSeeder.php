<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            'id' => 1,
            'name' => 'Super Admin',
            'email' => 'superadmin@yopmail.com',
            'password' => Hash::make('mind@123'),
            'created_at'=>date('Y-m-d H:i:s', rand(1662100000, 1662113343)),
           
        ]);
    }
}