<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
  /**
   * The base URL to use while testing the application.
   *
   * @var string
   */
  protected $baseUrl = 'http://localhost';

  /**
   * Creates the application.
   *
   * @return \Illuminate\Foundation\Application
   */
  public function createApplication()
  {
    $app = require __DIR__.'/../bootstrap/app.php';

    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    return $app;
  }

  /**
   * Create users and login the specified role
   *
   * @return \Illuminate\Foundation\Testing\TestCase
   */
  protected function createUsers($role = null)
  {
    $this->admin = factory(\App\Models\Employee::class, 'admin')->create();
    $this->supervisor = factory(\App\Models\Employee::class, 'supervisor')->create();
    $this->employee = factory(\App\Models\Employee::class, 'employee')->create();

    $this->employee->supervisor_id = $this->supervisor->id;
    $this->employee->save();

    if (!empty($role)) {
      $this->login($this->$role);
    }

    return $this;
  }

  /**
   * Authenticate a user.
   *
   * @return \Illuminate\Foundation\Testing\TestCase
   */
  protected function login($user)
  {
    if (is_string($user)) {
      $user = $this->$user;
    }

    return $this->visit('/login')
                ->type($user->email, 'email')
                ->type('m4dg3n1us', 'password')
                ->press('Sign In');
  }

  /**
   * Log user out.
   *
   * @return \Illuminate\Foundation\Testing\TestCase
   **/
  protected function logout()
  {
    return $this->visit('/logout');
  }
}
