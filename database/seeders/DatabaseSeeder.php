<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(DefaultBookTableSeeder::class);
        $this->call(DefaultUserTableSeeder::class);
        $this->call(DefaultCatergoryTableSeeder::class);
    }
}
