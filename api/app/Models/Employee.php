<?php

namespace App\Models;

use App\Http\Requests\EmployeeRequest;
use App\Services\TimeOffService;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * Class Employee
 *
 * @package App\Models
 */
class Employee extends \Eloquent implements AuthenticatableContract,
                                            AuthorizableContract,
                                            CanResetPasswordContract
{
    use Authenticatable,
        Authorizable,
        CanResetPassword,
        RevisionableTrait,
        SoftDeletes;

    /**
     * The database table used by the model
     *
     * @var string
     **/
    protected $table = 'employees';

    /**
     * Fields that can be mass assigned
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'hire_date',
        'birth_date',
        'company',
        'department',
        'title',
        'address',
        'phone',
        'supervisor_id',
    ];
    /**
     * Fields hidden from view
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Fields to be converted to Carbon instances
     *
     * @var array
     */
    protected $dates = ['hire_date', 'birth_date', 'created_at', 'updated_at'];

    /**
     * Boot static method for the RevisionableTrait
     *
     * @return void
     **/
    public static function boot()
    {
        parent::boot();
    }

    /**
     * Combines the first_name and last_name
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->attributes['first_name'] . ' ' . $this->attributes['last_name'];
    }

    /**
     * Returns the number of vacation days used
     *
     * @return int
     **/
    public function getVacationUsedAttribute()
    {
        return $this->ptoUsed('vacation');
    }

    /**
     * Returns the number of sick days used
     *
     * @return int
     **/
    public function getSickUsedAttribute()
    {
        return $this->ptoUsed('sick');
    }

    /**
     * Returns the number of bereavement days used
     *
     * @return int
     **/
    public function getBereavementUsedAttribute()
    {
        return $this->ptoUsed('bereavement');
    }

    /**
     * Returns the number of unpaid days used
     *
     * @return int
     **/
    public function getUnpaidUsedAttribute()
    {
        return $this->ptoUsed('unpaid');
    }

    /**
     * Encrypt the password
     *
     * @return void
     **/
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * Persists the submitted photo to the filesystem
     *
     * @return void
     **/
    public function setPhotoAttribute($file)
    {
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        $filePath = 'images/uploads/' . $fileName;
        $thumbPath = 'images/uploads/thumbs/' . $fileName;
        $image = \Image::make($file)->fit(320, 320, null, 'top')->save($filePath);
        $thumbnail = $image->fit(64)->save($thumbPath);
        $this->attributes['photo'] = '/' . $filePath;
        $this->attributes['thumbnail'] = '/' . $thumbPath;
    }

    /**
     * Return the number of days used for each PTO request type
     *
     * @param  $type string
     * @return integer
     **/
    protected function ptoUsed($type)
    {
        $days = 0;

        foreach ($this->requests()->ofType($type)->approved()->get() as $req) {
            $days += $req->days;
        }

        return $days;
    }

    /**
     * The employee's associated supervisor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supervisor()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * The supervisor's associated subordinates
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'supervisor_id');
    }

    /**
     * The employee's associated requests
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    /**
     * The employee's associated role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Returns true if the user has the role 'Admin'
     *
     * @return boolean
     **/
    public function isAdmin()
    {
        return $this->role->id === Role::whereName('Admin')->first()->id;
    }

    /**
     * Returns true if the user has the role 'Supervisor'
     *
     * @return boolean
     **/
    public function isSupervisor()
    {
        return $this->role->id === Role::whereName('Supervisor')->first()->id;
    }

    /**
     * Returns true if the user has any elevated privilages
     *
     * @return boolean
     **/
    public function isElevated()
    {
        return $this->isAdmin() || $this->isSupervisor();
    }

    /**
     * Return the number of total vacation days
     *
     * @return integer
     */
    public function getVacationDaysAttribute()
    {
        return (new TimeOffService($this))->getVacationDays();
    }

    /**
     * Return the number of total sick days
     *
     * @return integer
     */
    public function getSickDaysAttribute()
    {
        return (new TimeOffService($this))->getSickDays();
    }
}
