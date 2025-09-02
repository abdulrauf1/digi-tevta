<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'course.create', 'course.view', 'course.edit', 'course.delete',
            'trainee.create', 'trainee.view', 'trainee.edit', 'trainee.delete',
            'trainer.create', 'trainer.view', 'trainer.edit', 'trainer.delete',
            'attendance.create', 'attendance.view', 'attendance.edit',
            'assessment.create', 'assessment.view', 'assessment.edit', 'assessment.delete',
            'lesson.create', 'lesson.view', 'lesson.edit',
            'user.manage', 'report.generate'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $admissionClerk = Role::create(['name' => 'admission-clerk']);
        $admissionClerk->givePermissionTo([
            'course.create', 'course.view',
            'trainee.create', 'trainee.view', 'trainee.edit',
            'trainer.create', 'trainer.view',
            'attendance.view',
            'assessment.view'
        ]);

        $trainer = Role::create(['name' => 'trainer']);
        $trainer->givePermissionTo([
            'course.view',
            'trainee.view',
            'attendance.create', 'attendance.view', 'attendance.edit',
            'assessment.create', 'assessment.view', 'assessment.edit',
            'lesson.create', 'lesson.view', 'lesson.edit'
        ]);

        $trainee = Role::create(['name' => 'trainee']);
        $trainee->givePermissionTo([
            'assessment.create', 'assessment.view', 'assessment.edit'
        ]);
    }
}
