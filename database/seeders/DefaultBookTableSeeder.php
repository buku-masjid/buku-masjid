<?php

namespace Database\Seeders;

use App\Models\Book;
use App\User;
use Illuminate\Database\Seeder;

class DefaultBookTableSeeder extends Seeder
{
    public function run(): void
    {
        $financeUser = User::where('role_id', User::ROLE_FINANCE)->where('is_active', 1)->first();

        Book::create([
            'name' => 'Kegiatan Rutin',
            'description' => 'Buku catatan keuangan kegiatan rutin',
            'creator_id' => null,
            'report_visibility_code' => 'public',
            'manager_id' => optional($financeUser)->id,
        ]);
    }
}
