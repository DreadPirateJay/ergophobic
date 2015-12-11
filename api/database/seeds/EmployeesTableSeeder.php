<?php

use App\Models\Employee;
use App\Models\Role;
use Illuminate\Database\Seeder;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('employees')->truncate();

        $admin = Employee::create([
            'first_name' => 'Admin',
            'last_name'  => 'User',
            'title'      => 'Admin',
            'email'      => 'admin@madg.com',
            'password'   => 'admin',
        ]);

        $supervisor = Employee::create([
            'first_name' => 'Supervisor',
            'last_name'  => 'User',
            'title'      => 'Supervisor',
            'email'      => 'supervisor@madg.com',
            'password'   => 'supervisor',
        ]);

        $employee = Employee::create([
            'first_name' => 'Employee',
            'last_name'  => 'User',
            'title'      => 'Employee',
            'email'      => 'employee@madg.com',
            'password'   => 'employee',
        ]);

        $employee->supervisor()->associate($supervisor)->save();

        $jay = Employee::create([
            'first_name' => 'Jay',
            'last_name'  => 'Earwood',
            'title'      => 'Admin',
            'email'      => 'jearwood@madg.com',
            'password'   => 'm4dg3n1us',
        ]);
    }
}
