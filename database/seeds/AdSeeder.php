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
            'title' => 'Honda Civic',
            'description'=> 'ST 180 hp',
            'price' => 26500,
            'quantity' => 2,
            'user_id' => 1,
            'img_url' => 'ec2cd26e-3964-4fba-937d-d786c79140c9',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);

        DB::table('ads')->insert([
            'title' => 'MacBook Pro',
            'description'=> '2019',
            'price' => 2599,
            'quantity' => 5,
            'user_id' => 2,
            'img_url' => 'a0f5a17e-0733-4da9-a504-a195045b22a0',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);

        DB::table('ads')->insert([
            'title' => "Queen's First Album",
            'description'=> 'Very nice',
            'price' => 7500,
            'quantity' => 1,
            'user_id' => 4,
            'img_url' => '12c73c93-3e1d-4652-962a-a71e0ef4abeb',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);

        DB::table('ads')->insert([
            'title' => "Pizza",
            'description'=> 'Nice pizza',
            'price' => 5,
            'quantity' => 100,
            'user_id' => 3,
            'img_url' => '5372c1e1-d006-48fd-933a-ed86128ef9fc',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
    }
}
