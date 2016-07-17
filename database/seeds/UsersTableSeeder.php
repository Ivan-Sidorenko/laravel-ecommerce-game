<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();
        
        App\User::truncate();

        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@onlymuscle.co.uk',
            'password' => bcrypt('password'),
            'remember_token' => 'nwLxPp6KHNXCTN8kMpHZpFXxdHmXAeKLff47oe2N',
            'is_admin'=>1,
            'created_at' => DB::raw('NOW()'),
            'updated_at' => DB::raw('NOW()'),
        ]);
    }
}
