<?php

use App\Models\Employee;
use App\Models\Request;
use Illuminate\Database\Seeder;

class RequestsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('requests')->truncate();

      $faker = \Faker\Factory::create();
      $thisMonth = date('Y-m');

      Request::create([
        'employee_id' => 3,
        'type' => 'sick',
        'start_date' => new Carbon\Carbon($thisMonth . '-02 00:00:00', 'America/Chicago'),
        'multi_day' => false,
        'status' => 'Approved',
        'comments' => 'Sick of work.'
      ]);

      Request::create([
        'employee_id' => 3,
        'type' => 'vacation',
        'start_date' => new Carbon\Carbon($thisMonth . '-05 00:00:00', 'America/Chicago'),
        'multi_day' => true,
        'end_date' => new Carbon\Carbon($thisMonth . '-07 00:00:00', 'America/Chicago'),
        'status' => 'Approved',
        'comments' => 'Holliday in Cambodia'
      ]);

      Request::create([
        'employee_id' => 3,
        'type' => 'sick',
        'start_date' => new Carbon\Carbon($thisMonth . '-26 00:00:00', 'America/Chicago'),
        'multi_day' => false,
        'comments' => 'Tequila Flu'
      ]);

      foreach (Employee::all()->except(3) as $employee) {
        foreach (range(0, $faker->numberBetween(0, 5)) as $idx) {
          $multi_day = $faker->boolean();
          $date = new Carbon\Carbon($faker->dateTimeThisYear()->format('Y-m-d'), 'America/Chicago');
          $start_date = $date->format('Y-m-d');
          $end_date = $multi_day ? $date->addDays(3)->format('Y-m-d') : null;

          Request::create([
            'employee_id' => $employee->id,
            'type' => $faker->randomElement(['vacation', 'sick', 'bereavement', 'unpaid']),
            'start_date' => $start_date,
            'multi_day' => $multi_day,
            'end_date' => $end_date,
            'status' => $faker->randomElement(['Pending', 'Approved', 'Denied']),
            'comments' => $faker->bs
          ]);
        }
      }
    }
}
