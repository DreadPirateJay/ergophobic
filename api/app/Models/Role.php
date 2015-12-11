<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
  /**
   * Fields that can be mass-assigned
   *
   * @var array
   **/
  protected $fillable = ['name'];

  /**
   * Returns the associated employees
   *
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   **/
  public function employees()
  {
    return $this->hasMany(Employee::class);
  }
}
