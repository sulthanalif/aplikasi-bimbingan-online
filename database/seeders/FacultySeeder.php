<?php

namespace Database\Seeders;

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

        Faculty::insert($faculties);
    }
}
