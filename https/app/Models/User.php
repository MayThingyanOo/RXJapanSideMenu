<?php

namespace App\Models;

use Hash;
use Illuminate\Support\Arr;

class User extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $connection = 'qpass';
    protected $table = 'users';

    protected $fillable = [
        'company',
        'department',
        'post',
        'name1',
        'name2',
        'tel',
        'status',
        'copro_charge',
        'note',
        'password',
        'allow_ip_address',
        'check_ip_address_mode',
        'domain_name',
        'ui_design',
        'sso_flag',
        'sso_info',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function exhibitions()
    {
        return $this->hasManyThrough('App\Models\Exhibition', 'App\Models\ExhibitionGroup');
    }
}
