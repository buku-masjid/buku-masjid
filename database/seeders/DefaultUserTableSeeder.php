<?php

namespace Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DefaultUserTableSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.net',
            'password' => bcrypt(config('auth.passwords.default')),
            'role_id' => User::ROLE_ADMIN,
            'api_token' => Str::random(24),
        ]);
        User::create([
            'name' => 'Ketua',
            'email' => 'ketua@example.net',
            'password' => bcrypt(config('auth.passwords.default')),
            'role_id' => User::ROLE_CHAIRMAN,
            'api_token' => Str::random(24),
        ]);
        User::create([
            'name' => 'Sekretaris',
            'email' => 'sekretaris@example.net',
            'password' => bcrypt(config('auth.passwords.default')),
            'role_id' => User::ROLE_SECRETARY,
            'api_token' => Str::random(24),
        ]);
        User::create([
            'name' => 'Bendahara',
            'email' => 'bendahara@example.net',
            'password' => bcrypt(config('auth.passwords.default')),
            'role_id' => User::ROLE_FINANCE,
            'api_token' => Str::random(24),
        ]);
    }
}
