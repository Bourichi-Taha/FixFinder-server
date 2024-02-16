<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $user = User::firstOrCreate(
        ['email' => 'user@example.com','firstname' => 'first','lastname' => 'last','phone' => '+212626661516','rating' => 4.5],
        ['password' => bcrypt('user')],
    );
    $user->assignRole('user');
    $admin = User::firstOrCreate(
        ['email' => 'admin@example.com','firstname' => 'second','lastname' => 'meduim','phone' => '+212626661516','rating' => 3.5],
        ['password' => bcrypt('admin')],
    );
    $admin->assignRole('admin');
  }
}
