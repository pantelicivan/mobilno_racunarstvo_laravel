<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Milos',
            'email'=> 'milos@mail.com',
            'password' => bcrypt('sifra'),
            'mobile_phone' => "+381694411832",
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('users')->insert([
            'name' => 'Ivan',
            'email'=> 'ivan@mail.com',
            'password' => bcrypt('sifra'),
            'mobile_phone' => "+381694411832",
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('users')->insert([
            'name' => 'Papi',
            'email'=> 'papi@mail.com',
            'password' => bcrypt('sifra'),
            'mobile_phone' => "+381694411832",
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('users')->insert([
            'name' => 'Duda',
            'email'=> 'duda@mail.com',
            'password' => bcrypt('sifra'),
            'mobile_phone' => "+381694411832",
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
    }
}
