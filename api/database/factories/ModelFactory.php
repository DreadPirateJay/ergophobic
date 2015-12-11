<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->defineAs(App\Models\Employee::class, 'employee', function ($faker)
{
    return [
        'first_name'         => $faker->firstNameMale,
        'last_name'          => $faker->lastName,
        'email'              => $faker->email,
        'password'           => 'm4dg3n1us',
        'role_id'            => App\Models\Role::where('name', 'Employee')->first()->id,
    ];
});

$factory->defineAs(App\Models\Employee::class, 'supervisor', function ($faker)
{
    return [
        'first_name'         => $faker->firstNameMale,
        'last_name'          => $faker->lastName,
        'email'              => $faker->email,
        'password'           => 'm4dg3n1us',
        'role_id'            => App\Models\Role::where('name', 'Supervisor')->first()->id,
    ];
});

$factory->defineAs(App\Models\Employee::class, 'admin', function ($faker)
{
    return [
        'first_name'         => $faker->firstNameMale,
        'last_name'          => $faker->lastName,
        'email'              => $faker->email,
        'password'           => 'm4dg3n1us',
        'role_id'            => App\Models\Role::where('name', 'Admin')->first()->id,
    ];
});

$factory->define(App\Models\Request::class, function ($faker)
{
    $employees = App\Models\Employee::all();
    $employee_id = $employees->random()->id;
    $start_date = new Carbon\Carbon($faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'));
    $multi_day = $faker->boolean;
    $end_date = $multi_day ? $start_date->addDays($faker->randomDigitNotNull) : null;
    $type = $faker->randomElement(['vacation', 'sick', 'bereavement', 'unpaid']);
    $comments = $faker->sentence;
    $status = $faker->randomElement(['Pending', 'Approved', 'Denied']);

    return [
        'employee_id' => $employee_id,
        'type'        => $type,
        'start_date'  => $start_date,
        'start_hours' => 8,
        'multi_day'   => $multi_day,
        'end_date'    => $end_date,
        'end_hours'   => 8,
        'status'      => $status,
        'comments'    => $comments,
    ];
});