<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('admins')->insert([
            [
                'username' => 'Admin',
                'password' => '$10$/KyvJrdynqVn/YlaYlvbee2QzxGjTKqb8VXD8uy8iXAMVtzJ6T7tO',
                'level' => 1,
                'full_name' => 'Võ Minh Trí',
                'email' => 'vominhtri0108@gmail.com',
                'phone' => '0123456789',
                'is_changedpw' => 1,
                'created_at' => 1588348417,
                'updated_at' => 1588348417
            ]
        ]);
    }
}
