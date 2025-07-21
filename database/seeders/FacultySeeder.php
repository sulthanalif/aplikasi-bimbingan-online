<?php

namespace Database\Seeders;

use App\Models\Topic;
use App\Models\Faculty;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faculties = [
            [
                'code' => 'FT',
                'name' => 'Fakultas Teknik',
                'description' => 'Fakultas Teknik'
            ],
            [
                'code' => 'FKOM',
                'name' => 'Fakultas Komputer',
                'description' => 'Fakultas Komputer'
            ],
        ];

        $topics = [
            [
                'faculty_id' => 1,
                'name' => 'Analisis Kinerja Mesin',
                'description' => 'Deskripsi Analisis Kinerja Mesin',
            ],
            [
                'faculty_id' => 1,
                'name' => 'Perancangan dan Pengembangan Produk',
                'description' => 'Deskripsi Perancangan dan Pengembangan Produk',
            ],
            [
                'faculty_id' => 1,
                'name' => 'Pengembangan Material',
                'description' => 'Deskripsi Pengembangan Material',
            ],
            [
                'faculty_id' => 2,
                'name' => 'Rancang Bangun',
                'description' => 'Deskripsi Rancang Bangun',
            ],
            [
                'faculty_id' => 2,
                'name' => 'Analisis Sistem',
                'description' => 'Deskripsi Analisis Sistem',
            ],
            [
                'faculty_id' => 2,
                'name' => 'Sistem Pendukung Keputusan',
                'description' => 'Deskripsi Sistem Pendukung Keputusan',
            ],
        ];

        Faculty::insert($faculties);
        Topic::insert($topics);
    }
}
