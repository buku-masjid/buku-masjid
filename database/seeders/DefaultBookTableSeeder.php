<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class DefaultBookTableSeeder extends Seeder
{
    public function run(): void
    {
        Book::create([
            'name' => 'Kegiatan Rutin',
            'description' => 'Buku catatan keuangan kegiatan rutin',
            'creator_id' => null,
            'report_visibility_code' => 'public',
        ]);
    }
}
