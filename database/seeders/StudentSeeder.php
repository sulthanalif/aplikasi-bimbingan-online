<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [
            // Teknik Mesin Students
            [
                'nim' => '2011101001',
                'name' => 'Budi Santoso',
                'email' => 'budisantoso@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Laki-laki',
                'address' => 'Jl. Raya Bogor No. 10',
                'phone' => '081234567801',
                'department_id' => 1
            ],
            [
                'nim' => '2011101002',
                'name' => 'Siti Aminah',
                'email' => 'sitiaminah@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Perempuan',
                'address' => 'Jl. Sudirman No. 15',
                'phone' => '081234567802',
                'department_id' => 1
            ],
            [
                'nim' => '2011101003',
                'name' => 'Ahmad Rizki',
                'email' => 'ahmadrizki@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Laki-laki',
                'address' => 'Jl. Gatot Subroto No. 20',
                'phone' => '081234567803',
                'department_id' => 1
            ],
            [
                'nim' => '2011101004',
                'name' => 'Dewi Lestari',
                'email' => 'dewilestari@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Perempuan',
                'address' => 'Jl. Thamrin No. 25',
                'phone' => '081234567804',
                'department_id' => 1
            ],
            [
                'nim' => '2011101005',
                'name' => 'Rudi Hermawan',
                'email' => 'rudihermawan@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Laki-laki',
                'address' => 'Jl. Asia Afrika No. 30',
                'phone' => '081234567805',
                'department_id' => 1
            ],

            // Teknik Elektro Students
            [
                'nim' => '2011102001',
                'name' => 'Andi Wijaya',
                'email' => 'andiwijaya@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Laki-laki',
                'address' => 'Jl. Diponegoro No. 35',
                'phone' => '081234567806',
                'department_id' => 2
            ],
            [
                'nim' => '2011102002',
                'name' => 'Nina Sari',
                'email' => 'ninasari@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Perempuan',
                'address' => 'Jl. Veteran No. 40',
                'phone' => '081234567807',
                'department_id' => 2
            ],
            [
                'nim' => '2011102003',
                'name' => 'Dodi Pratama',
                'email' => 'dodipratama@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Laki-laki',
                'address' => 'Jl. Pahlawan No. 45',
                'phone' => '081234567808',
                'department_id' => 2
            ],
            [
                'nim' => '2011102004',
                'name' => 'Maya Putri',
                'email' => 'mayaputri@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Perempuan',
                'address' => 'Jl. Merdeka No. 50',
                'phone' => '081234567809',
                'department_id' => 2
            ],
            [
                'nim' => '2011102005',
                'name' => 'Eko Susanto',
                'email' => 'ekosusanto@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Laki-laki',
                'address' => 'Jl. Pemuda No. 55',
                'phone' => '081234567810',
                'department_id' => 2
            ],

            // Teknik Sipil Students
            [
                'nim' => '2011103001',
                'name' => 'Bambang Kusuma',
                'email' => 'bambangkusuma@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Laki-laki',
                'address' => 'Jl. Ahmad Yani No. 60',
                'phone' => '081234567811',
                'department_id' => 3
            ],
            [
                'nim' => '2011103002',
                'name' => 'Rina Wati',
                'email' => 'rinawati@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Perempuan',
                'address' => 'Jl. Gajah Mada No. 65',
                'phone' => '081234567812',
                'department_id' => 3
            ],
            [
                'nim' => '2011103003',
                'name' => 'Agus Setiawan',
                'email' => 'agussetiawan@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Laki-laki',
                'address' => 'Jl. Hayam Wuruk No. 70',
                'phone' => '081234567813',
                'department_id' => 3
            ],
            [
                'nim' => '2011103004',
                'name' => 'Linda Melati',
                'email' => 'lindamelati@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Perempuan',
                'address' => 'Jl. Kartini No. 75',
                'phone' => '081234567814',
                'department_id' => 3
            ],
            [
                'nim' => '2011103005',
                'name' => 'Hendra Gunawan',
                'email' => 'hendragunawan@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Laki-laki',
                'address' => 'Jl. Cut Nyak Dien No. 80',
                'phone' => '081234567815',
                'department_id' => 3
            ],

            // Teknik Informatika Students
            [
                'nim' => '2011104001',
                'name' => 'Dian Sastro',
                'email' => 'diansastro@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Perempuan',
                'address' => 'Jl. Imam Bonjol No. 85',
                'phone' => '081234567816',
                'department_id' => 4
            ],
            [
                'nim' => '2011104002',
                'name' => 'Fajar Ramadhan',
                'email' => 'fajarramadhan@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Laki-laki',
                'address' => 'Jl. Antasari No. 90',
                'phone' => '081234567817',
                'department_id' => 4
            ],
            [
                'nim' => '2011104003',
                'name' => 'Putri Indah',
                'email' => 'putriindah@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Perempuan',
                'address' => 'Jl. Sisingamangaraja No. 95',
                'phone' => '081234567818',
                'department_id' => 4
            ],
            [
                'nim' => '2011104004',
                'name' => 'Rizal Ibrahim',
                'email' => 'rizalibrahim@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Laki-laki',
                'address' => 'Jl. Pangeran Diponegoro No. 100',
                'phone' => '081234567819',
                'department_id' => 4
            ],
            [
                'nim' => '2011104005',
                'name' => 'Anisa Rahma',
                'email' => 'anisarahma@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Perempuan',
                'address' => 'Jl. Sultan Agung No. 105',
                'phone' => '081234567820',
                'department_id' => 4
            ],

            // Sistem Informasi Students
            [
                'nim' => '2011105001',
                'name' => 'Yoga Pranata',
                'email' => 'yogapranata@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Laki-laki',
                'address' => 'Jl. Teuku Umar No. 110',
                'phone' => '081234567821',
                'department_id' => 5
            ],
            [
                'nim' => '2011105002',
                'name' => 'Dina Maulida',
                'email' => 'dinamaulida@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Perempuan',
                'address' => 'Jl. Wahid Hasyim No. 115',
                'phone' => '081234567822',
                'department_id' => 5
            ],
            [
                'nim' => '2011105003',
                'name' => 'Irfan Hakim',
                'email' => 'irfanhakim@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Laki-laki',
                'address' => 'Jl. KH Agus Salim No. 120',
                'phone' => '081234567823',
                'department_id' => 5
            ],
            [
                'nim' => '2011105004',
                'name' => 'Ratna Sari',
                'email' => 'ratnasari@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Perempuan',
                'address' => 'Jl. Cokroaminoto No. 125',
                'phone' => '081234567824',
                'department_id' => 5
            ],
            [
                'nim' => '2011105005',
                'name' => 'Dimas Prayoga',
                'email' => 'dimasprayoga@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Laki-laki',
                'address' => 'Jl. Suryo No. 130',
                'phone' => '081234567825',
                'department_id' => 5
            ],

            // Manajemen Informatika Students
            [
                'nim' => '2011106001',
                'name' => 'Aditya Pratama',
                'email' => 'adityapratama@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Laki-laki',
                'address' => 'Jl. Juanda No. 135',
                'phone' => '081234567826',
                'department_id' => 6
            ],
            [
                'nim' => '2011106002',
                'name' => 'Nadia Safitri',
                'email' => 'nadiasafitri@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Perempuan',
                'address' => 'Jl. Supomo No. 140',
                'phone' => '081234567827',
                'department_id' => 6
            ],
            [
                'nim' => '2011106003',
                'name' => 'Galih Permana',
                'email' => 'galihpermana@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Laki-laki',
                'address' => 'Jl. Sutomo No. 145',
                'phone' => '081234567828',
                'department_id' => 6
            ],
            [
                'nim' => '2011106004',
                'name' => 'Kartika Dewi',
                'email' => 'kartikadewi@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Perempuan',
                'address' => 'Jl. Agus Salim No. 150',
                'phone' => '081234567829',
                'department_id' => 6
            ],
            [
                'nim' => '2011106005',
                'name' => 'Bayu Segara',
                'email' => 'bayusegara@mail.com',
                'password' => Hash::make('password'),
                'gender' => 'Laki-laki',
                'address' => 'Jl. Yos Sudarso No. 155',
                'phone' => '081234567830',
                'department_id' => 6
            ]
        ];

        foreach($students as $student) {
            $user = User::create([
                'name' => $student['name'],
                'email' => $student['email'],
                'password' => $student['password'],
            ]);
            $user->assignRole('mahasiswa');

            Student::create([
                'user_id' => $user->id,
                'nim' => $student['nim'],
                'gender' => $student['gender'],
                'address' => $student['address'],
                'phone' => $student['phone'],
                'department_id' => $student['department_id']
            ]);

        }
    }
}
