<?php

namespace App\Models;

class Staff extends CPSUserAuthenticatable
{
    protected $connection = 'qpass';
    protected $softDeletes = true;
    protected $table = 'staffs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'exhibition_id',
        'name',
        'department_name',
        'email',
        'user_id',
        'password',
        'is_super_user_flag',
        'visitor_permission_level',
        'form_permission_level',
        'spot_permission_level',
        'seminar_permission_level',
        'staff_permission_level',
        'exhibition_permission_level',
        'mail_permission_level',
        'qanalyzer_permission_level',
        'qentry_permission_level',
        'qentry_activation_permission_level',
        'staff_code',
        'staff_pwd_reset_flag',
    ];

    protected $hidden = ['password','created_by','updated_by'];

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'user_id', 'user_id');
    }
}
