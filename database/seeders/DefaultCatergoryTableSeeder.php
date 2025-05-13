<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class DefaultCatergoryTableSeeder extends Seeder
{
    public function run(): void
    {
        Category::create(['name' => 'Kotak infak Hari Jumat', 'color' => '#00AABB', 'report_visibility_code' => Category::REPORT_VISIBILITY_PUBLIC, 'creator_id' => 1, 'book_id' => 1]);
        Category::create(['name' => 'Kotak infak Kajian', 'color' => '#00AABB', 'report_visibility_code' => Category::REPORT_VISIBILITY_PUBLIC, 'creator_id' => 1, 'book_id' => 1]);
        Category::create(['name' => 'Kotak infak Harian', 'color' => '#00AABB', 'report_visibility_code' => Category::REPORT_VISIBILITY_PUBLIC, 'creator_id' => 1, 'book_id' => 1]);
        Category::create(['name' => 'Pemasukan infak Lain-lain', 'color' => '#00AABB', 'report_visibility_code' => Category::REPORT_VISIBILITY_PUBLIC, 'creator_id' => 1, 'book_id' => 1]);
        Category::create(['name' => 'Gaji Karyawan', 'color' => '#F16867', 'report_visibility_code' => Category::REPORT_VISIBILITY_INTERNAL, 'creator_id' => 1, 'book_id' => 1]);
        Category::create(['name' => 'Insentif Hari Jumat', 'color' => '#F16867', 'report_visibility_code' => Category::REPORT_VISIBILITY_PUBLIC, 'creator_id' => 1, 'book_id' => 1]);
        Category::create(['name' => 'Insentif Kajian', 'color' => '#F16867', 'report_visibility_code' => Category::REPORT_VISIBILITY_PUBLIC, 'creator_id' => 1, 'book_id' => 1]);
        Category::create(['name' => 'Konsumsi Kajian', 'color' => '#F16867', 'report_visibility_code' => Category::REPORT_VISIBILITY_PUBLIC, 'creator_id' => 1, 'book_id' => 1]);
        Category::create(['name' => 'Tagihan Air', 'color' => '#F16867', 'report_visibility_code' => Category::REPORT_VISIBILITY_PUBLIC, 'creator_id' => 1, 'book_id' => 1]);
        Category::create(['name' => 'Tagihan Listrik', 'color' => '#F16867', 'report_visibility_code' => Category::REPORT_VISIBILITY_PUBLIC, 'creator_id' => 1, 'book_id' => 1]);
        Category::create(['name' => 'Tagihan Internet', 'color' => '#F16867', 'report_visibility_code' => Category::REPORT_VISIBILITY_PUBLIC, 'creator_id' => 1, 'book_id' => 1]);
        Category::create(['name' => 'Biaya Lain-lain', 'color' => '#F16867', 'report_visibility_code' => Category::REPORT_VISIBILITY_PUBLIC, 'creator_id' => 1, 'book_id' => 1]);
        Category::create(['name' => 'Pengambilan Di BANK', 'color' => '#00AABB', 'report_visibility_code' => Category::REPORT_VISIBILITY_PUBLIC, 'creator_id' => 1, 'book_id' => 1]);
    }
}
