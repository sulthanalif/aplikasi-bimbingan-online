<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            // Engineering Faculty Departments (faculty_id = 1)
            [
                'code' => 'TM',
                'faculty_id' => 1,
                'name' => 'Teknik Mesin',
                'description' => 'Program Studi Teknik Mesin'
            ],
            [
                'code' => 'TE',
                'faculty_id' => 1,
                'name' => 'Teknik Elektro',
                'description' => 'Program Studi Teknik Elektro'
            ],
            [
                'code' => 'TS',
                'faculty_id' => 1,
                'name' => 'Teknik Sipil',
                'description' => 'Program Studi Teknik Sipil'
            ],

            // Computer Faculty Departments (faculty_id = 2)
            [
                'code' => 'TI',
                'faculty_id' => 2,
                'name' => 'Teknik Informatika',
                'description' => 'Program Studi Teknik Informatika'
            ],
            [
                'code' => 'SI',
                'faculty_id' => 2,
                'name' => 'Sistem Informasi',
                'description' => 'Program Studi Sistem Informasi'
            ],
            [
                'code' => 'MI',
                'faculty_id' => 2,
                'name' => 'Manajemen Informatika',
                'description' => 'Program Studi Manajemen Informatika'
            ],
        ];

        Department::insert($departments);
    }
}
