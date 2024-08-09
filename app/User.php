<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'private_tasks_view', 'rate', 'current_company'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isRoot()
    {
        return in_array($this->id, [
            1, 2
        ]);
    }

    public function isOfficePerson()
    {
        return true;
    }

    public function projectManager()
    {
        return in_array($this->id, [
            16
        ]);
    }

    public function isTracking($employee, $severity)
    {
        return TimeTracker::where('collab_user', auth()->user()->collab_user)
                            ->where('tracking_user', $employee->id)
                            ->where('severity_level', $severity)->first();
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'current_company');
    }

}
