<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Lecturer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LecturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lecturers = [
            [
                'name' => 'Ibrahim',
                'email' => 'ibrahim@mail.com',
                'password' => 'password',
                'faculty_id' => 1,
                'nip' => '1234567890',
                'gender' => 'Laki-laki',
                'address' => 'Jl. Sudirman No. 123',
                'phone' => '081234567890',
                'status' => 'true'
            ],
            [
                'name' => 'Ahmad',
                'email' => 'ahmad@mail.com',
                'password' => 'password',
                'faculty_id' => 1,
                'nip' => '1234567891',
                'gender' => 'Laki-laki',
                'address' => 'Jl. Thamrin No. 45',
                'phone' => '081234567891',
                'status' => 'true'
            ],
            [
                'name' => 'Sarah',
                'email' => 'sarah@mail.com',
                'password' => 'password',
                'faculty_id' => 1,
                'nip' => '1234567892',
                'gender' => 'Perempuan',
                'address' => 'Jl. Gatot Subroto No. 67',
                'phone' => '081234567892',
                'status' => 'true'
            ],
            [
                'name' => 'Fatima',
                'email' => 'fatima@mail.com',
                'password' => 'password',
                'faculty_id' => 1,
                'nip' => '1234567893',
                'gender' => 'Perempuan',
                'address' => 'Jl. Asia Afrika No. 89',
                'phone' => '081234567893',
                'status' => 'true'
            ],
            [
                'name' => 'Yusuf',
                'email' => 'yusuf@mail.com',
                'password' => 'password',
                'faculty_id' => 1,
                'nip' => '1234567894',
                'gender' => 'Laki-laki',
                'address' => 'Jl. Diponegoro No. 12',
                'phone' => '081234567894',
                'status' => 'true'
            ],
            [
                'name' => 'Rahmat',
                'email' => 'rahmat@mail.com',
                'password' => 'password',
                'faculty_id' => 2,
                'nip' => '1234567895',
                'gender' => 'Laki-laki',
                'address' => 'Jl. Merdeka No. 34',
                'phone' => '081234567895',
                'status' => 'true'
            ],
            [
                'name' => 'Aminah',
                'email' => 'aminah@mail.com',
                'password' => 'password',
                'faculty_id' => 2,
                'nip' => '1234567896',
                'gender' => 'Perempuan',
                'address' => 'Jl. Veteran No. 56',
                'phone' => '081234567896',
                'status' => 'true'
            ],
            [
                'name' => 'Hassan',
                'email' => 'hassan@mail.com',
                'password' => 'password',
                'faculty_id' => 2,
                'nip' => '1234567897',
                'gender' => 'Laki-laki',
                'address' => 'Jl. Pahlawan No. 78',
                'phone' => '081234567897',
                'status' => 'true'
            ],
            [
                'name' => 'Laila',
                'email' => 'laila@mail.com',
                'password' => 'password',
                'faculty_id' => 2,
                'nip' => '1234567898',
                'gender' => 'Perempuan',
                'address' => 'Jl. Gajah Mada No. 90',
                'phone' => '081234567898',
                'status' => 'true'
            ],
            [
                'name' => 'Karim',
                'email' => 'karim@mail.com',
                'password' => 'password',
                'faculty_id' => 2,
                'nip' => '1234567899',
                'gender' => 'Laki-laki',
                'address' => 'Jl. Hayam Wuruk No. 23',
                'phone' => '081234567899',
                'status' => 'true'
            ]
        ];

        foreach($lecturers as $lecturer) {
            $user = User::create([
                'name' => $lecturer['name'],
                'email' => $lecturer['email'],
                'password' => Hash::make($lecturer['password'])
            ]);

            $user->assignRole('dosen');

            Lecturer::create([
                'user_id' => $user->id,
                'faculty_id' => $lecturer['faculty_id'],
                'nip' => $lecturer['nip'],
                'gender' => $lecturer['gender'],
                'address' => $lecturer['address'],
                'phone' => $lecturer['phone'],
            ]);
        }
    }
}
