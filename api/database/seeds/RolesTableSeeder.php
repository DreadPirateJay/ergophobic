<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('roles')->truncate();

      foreach (['Employee', 'Supervisor', 'Admin'] as $role) {
        Role::create(['name' => $role]);
      }

      foreach (App\Models\Employee::all() as $emp) {
        if ($emp->title === 'Admin') {
          $emp->role_id = 3;
        } else if ($emp->title === 'Supervisor') {
          $emp->role_id = 2;
        } else {
          $emp->role_id = 1;
        }

        $emp->save();
      }
    }
}
