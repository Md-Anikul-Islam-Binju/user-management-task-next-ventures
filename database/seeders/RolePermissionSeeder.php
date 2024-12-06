<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{

    public function run(): void
    {
        // Create Permissions
        $createBlogPermission = Permission::firstOrCreate(['name' => 'create-blog']);
        $editBlogPermission = Permission::firstOrCreate(['name' => 'edit-blog']);
        $viewBlogPermission = Permission::firstOrCreate(['name' => 'view-blog']);
        $deleteBlogPermission = Permission::firstOrCreate(['name' => 'delete-blo']);

        // Create Roles
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Assign Permissions to Roles
        $userRole->permissions()->sync([$createBlogPermission->id, $editBlogPermission->id, $viewBlogPermission->id, $deleteBlogPermission->id]);

    }
}
