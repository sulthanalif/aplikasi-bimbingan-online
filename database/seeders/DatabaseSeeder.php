<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roleSuperAdmin = Role::create(['name' => 'super-admin']);
        $roleProdi = Role::create(['name' => 'prodi']);
        $roleMahasiswa = Role::create(['name' => 'mahasiswa']);
        $roleDosen = Role::create(['name' => 'dosen']);


        $permissions = [
            'dashboard',

            'master-data',
            'manage-faculties',
            'create-faculty',
            'edit-faculty',
            'delete-faculty',
            'manage-departments',
            'create-department',
            'edit-department',
            'delete-department',
            'manage-users',
            'manage-students',
            'create-student',
            'edit-student',
            'delete-student',
            'manage-lecturers',
            'create-lecturer',
            'edit-lecturer',
            'delete-lecturer',
            'manage-topics',
            'create-topic',
            'edit-topic',
            'delete-topic',

            'main-menu',
            'manage-theses',
            'create-thesis',
            'action-thesis',


            'settings',
            'manage-permissions',
            'manage-roles',
            'manage-logs',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $roleSuperAdmin->givePermissionTo($permissions);

        $roleProdi->givePermissionTo([
            'dashboard',

            'master-data',
            'manage-faculties',
            'create-faculty',
            'edit-faculty',
            'delete-faculty',
            'manage-departments',
            'create-department',
            'edit-department',
            'delete-department',

            'manage-students',
            'create-student',
            'edit-student',
            'delete-student',
            'manage-lecturers',
            'create-lecturer',
            'edit-lecturer',
            'delete-lecturer',
            'manage-topics',
            'create-topic',
            'edit-topic',
            'delete-topic',

            'main-menu',
            'manage-theses',
            'action-thesis',
        ]);

        $roleMahasiswa->givePermissionTo([
            'dashboard',
            'main-menu',
            'manage-theses',
            'create-thesis',
        ]);

        $roleDosen->givePermissionTo([
            'dashboard',
            'main-menu',
            // 'manage-theses',
            // 'create-thesis',
        ]);

        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@mail.com',
        ]);

        $superAdmin->assignRole($roleSuperAdmin);



        $this->call([
            FacultySeeder::class,
            DepartmentSeeder::class,
            StudentSeeder::class,
            LecturerSeeder::class,
        ]);

        $prodi = User::factory()->create([
            'name' => 'Prodi',
            'email' => 'prodi@mail.com',
        ]);

        $prodi->assignRole($roleProdi);

        $mahasiswa = User::factory()->create([
            'name' => 'Mahasiswa',
            'email' => 'mahasiswa@mail.com',
        ]);

        $mahasiswa->assignRole($roleMahasiswa);

        $mahasiswa->student()->create([
            'nim' => '345435634',
            'gender' => 'Laki-laki',
            'address' => 'Jl. Raya Bogor No. 10',
            'phone' => '081234567801',
            'department_id' => 5
        ]);

        $dosen = User::factory()->create([
            'name' => 'Dosen',
            'email' => 'dosen@mail.com',
        ]);

        $dosen->assignRole($roleDosen);

        $dosen->lecturer()->create([
            'nip' => '84774394534',
            'gender' => 'Laki-laki',
            'address' => 'Jl. Sudirman No. 11',
            'phone' => '081234567802',
            'faculty_id' => 2
        ]);
    }
}
