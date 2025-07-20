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


            'settings',
            'manage-permissions',
            'manage-roles',
            'manage-logs',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $roleSuperAdmin->givePermissionTo($permissions);

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
    }
}
