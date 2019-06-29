<?php

use Illuminate\Database\Seeder;

class AdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ads')->insert([
            'title' => 'Ad 1',
            'description'=> 'Description',
            'price' => 100,
            'quantity' => 5,
            'user_id' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);

        DB::table('ads')->insert([
            'title' => 'Ad 2',
            'description'=> 'Description',
            'price' => 200,
            'quantity' => 5,
            'user_id' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);

        DB::table('ads')->insert([
            'title' => 'Ad 3',
            'description'=> 'Description',
            'price' => 300,
            'quantity' => 5,
            'user_id' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
    }
}
