<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class CrudPermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    /*
      Here, include project specific permissions. E.G.: */
      $this->createScopePermissions('uploads', ['create', 'read', 'update', 'delete',]);
      $this->createScopePermissions('reviews', ['create', 'read', 'read_own', 'update', 'delete']);
      $this->createScopePermissions('locations', ['create', 'read', 'read_own', 'update', 'delete']);
      $this->createScopePermissions('categories', ['create', 'read', 'read_own', 'update', 'delete']);
      $this->createScopePermissions('notifications', ['create', 'read', 'read_own', 'update', 'delete']);
      $this->createScopePermissions('bookings', ['create', 'read', 'read_own', 'update', 'delete']);

      $adminRole = Role::where('name', 'admin')->first();
      $this->assignScopePermissionsToRole($adminRole, 'uploads', ['create', 'read', 'update', 'delete',]);
      $this->assignScopePermissionsToRole($adminRole, 'reviews', ['create', 'read', 'read_own', 'update', 'delete']);
      $this->assignScopePermissionsToRole($adminRole, 'locations', ['create', 'read', 'read_own', 'update', 'delete']);
      $this->assignScopePermissionsToRole($adminRole, 'categories', ['create', 'read', 'read_own', 'update', 'delete']);
      $this->assignScopePermissionsToRole($adminRole, 'notifications', ['create', 'read', 'read_own', 'update', 'delete']);
      $this->assignScopePermissionsToRole($adminRole, 'bookings', ['create', 'read', 'read_own', 'update', 'delete']);

      $providerRole = Role::where('name', 'provider')->first();
      $this->assignScopePermissionsToRole($providerRole, 'uploads', ['create', 'read', 'update', 'delete',]);
      $this->assignScopePermissionsToRole($providerRole, 'locations', ['create', 'read', 'update', 'delete',]);
      $this->assignScopePermissionsToRole($providerRole, 'bookings', ['create', 'read_own']);
      $this->assignScopePermissionsToRole($providerRole, 'notifications', ['read']);
      $this->assignScopePermissionsToRole($providerRole, 'categories', ['read']);
      $this->assignScopePermissionsToRole($providerRole, 'reviews', ['create', 'read', 'read_own']);
      $userRole = Role::where('name', 'user')->first();
      $this->assignScopePermissionsToRole($userRole, 'uploads', ['create', 'read', 'update', 'delete',]);
      $this->assignScopePermissionsToRole($userRole, 'locations', ['create', 'read', 'update', 'delete',]);
      $this->assignScopePermissionsToRole($userRole, 'bookings', ['create', 'read_own']);
      $this->assignScopePermissionsToRole($userRole, 'notifications', ['read']);
      $this->assignScopePermissionsToRole($userRole, 'categories', ['read']);
      $this->assignScopePermissionsToRole($userRole, 'reviews', ['create', 'read', 'read_own']);



   
  }

  public function createRole(string $name): Role
  {
    $role = Role::firstOrCreate(['name' => $name]);
    return $role;
  }
  public function createScopePermissions(string $scope, array $permissions): void
  {
    foreach ($permissions as $permission) {
      Permission::firstOrCreate(['name' => $scope . '.' . $permission]);
    }
  }
  public function assignScopePermissionsToRole(Role $role, string $scope, array $permissions): void
  {
    foreach ($permissions as $permission) {
      $permissionName = $scope . '.' . $permission;

      if (!$role->hasPermission($permissionName)) {
        $role->givePermission($permissionName);
      }
    }
  }
}
