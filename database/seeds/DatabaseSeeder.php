<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(DefaultBookTableSeeder::class);
        $this->call(DefaultUserTableSeeder::class);
    }
}
