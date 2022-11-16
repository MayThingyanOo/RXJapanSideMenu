<?php

namespace App\Models;

class StaffPasswordReminder extends BaseModel
{
    protected $connection = 'qpass';
    protected $primaryKey = 'staff_password_reminder_id';
    protected $table = 'staff_password_reminder';

    protected $fillable = [
        'staff_id',
        'hash',
        'is_used',
        'reset_at',
        'created_by',
        'updated_by',
    ];

    public function staff()
    {
        return $this->belongsTo('App\Models\Staff');
    }
}
