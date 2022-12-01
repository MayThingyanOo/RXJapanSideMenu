<?php

namespace App\Models;

class ExhibitionDate extends BaseModel
{
    public $timestamps = false;
    protected $connection = 'qpass';
    protected $table = 'exhibition_dates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['exhibition_id', 'day', 'open_time', 'end_time'];

    public function getDayAttribute($value)
    {
        return format_date($value);
    }

    public function getOpenHourAttribute()
    {
        return substr($this->open_time, 0, strpos($this->open_time, ":"));
    }

    public function getOpenMinuteAttribute()
    {
        return substr($this->open_time, strpos($this->open_time, ":") + 1);
    }

    public function getEndHourAttribute()
    {
        return substr($this->end_time, 0, strpos($this->end_time, ":"));
    }

    public function getEndMinuteAttribute()
    {
        return substr($this->end_time, strpos($this->end_time, ":") + 1);
    }

    public function getOpenTimeAttribute($value)
    {
        return empty($value) ? null : format_time($value);
    }

    public function getEndTimeAttribute($value)
    {
        return empty($value) ? null : format_time($value);
    }
}
