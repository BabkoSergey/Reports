<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class RolesAndPermissionsSeeder extends Seeder {

    public function run() {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
    }

}

class PermissionSeeder extends Seeder {

    public function run() {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'admin panel',
            
            'show permission',
            'add permission',
            'edit permission',
            'delete permission',
            
            'show roles',
            'add roles',
            'edit roles',
            'delete roles',     
            
            'show settings',
            'edit settings',
            
            'show users',
            'add users',
            'edit users',
            'delete users',
            
            'setting translate',            
            'setting office',
            
            'office positions edit',
            'office positions list',
            
            'show projects',
            'add projects',
            'edit projects',
            'delete projects',            
            
            'show estimates',
            'add estimates',
            'edit estimates',
            'delete estimates', 
            'fill estimates',
                        
            'show tasks',
            'add tasks',
            'edit tasks',
            'delete tasks',
            
            'append resources',
            'append tasks',
                        
            'show dev_reports',
            
            'show dev_report',
            'add dev_report',
            'edit dev_report',
            'delete dev_report',
            
            'reports dev_report',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

}

class RoleSeeder extends Seeder {

    public function run() {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = [
            'SuperAdmin',
            'Admin',
            'Office',
            'Developer',
            'Manager'
        ];

        foreach ($roles as $role) {
            $addedRole = Role::create(['name' => $role]);

            switch ($role) {
                case 'SuperAdmin':
                    $addedRole->givePermissionTo(Permission::all());
                    
                    $user = User::where('name', 'superadmin')->first();
                    if ($user)
                        $user->assignRole(['SuperAdmin']);
                    break;
                case 'Admin':
                    $addedRole->givePermissionTo(Permission::all());
                    $addedRole->revokePermissionTo([
                                            'show permission',
                                            'add permission',
                                            'edit permission',
                                            'delete permission',
                            ]);

                    $user = User::where('name', 'admin')->first();
                    if ($user)
                        $user->assignRole(['Admin']);
                    break;
                default :                    
                    break;
            }
        }
    }

}
