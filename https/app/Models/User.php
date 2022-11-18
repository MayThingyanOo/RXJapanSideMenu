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

    public function super_user()
    {
        return $this->hasOne('App\Models\Staff')->where("is_super_user_flag", true);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function exhibitions()
    {
        return $this->hasManyThrough('App\Models\Exhibition', 'App\Models\ExhibitionGroup');
    }

    public function exhibition_groups()
    {
        return $this->hasMany('App\Models\ExhibitionGroup');
    }

    public function getId()
    {
        return $this->id;
    }
}
