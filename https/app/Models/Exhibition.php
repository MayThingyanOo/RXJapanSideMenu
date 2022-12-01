<?php

namespace App\Models;

class Exhibition extends BaseModel
{
    protected $connection = 'qpass';
    protected $softDeletes = true;
    protected $table = 'exhibitions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'exhibition_group_id',
        'qa',
        'order',
        'name',
        'place',
        'current_visitor_code',
        'open_datetime',
        'end_datetime',
        'entry_ticket_id',
        'note',
        'corporate_company_flag',
        'password_flag',
        'mypage_start_date',
        'mypage_end_date',
        'document_start_date',
        'document_end_date',
        'sponsor_start_date',
        'sponsor_end_date',
        'mail_confirm_flag',
        'streaming_document_merging',
        'mypage_top_flag',
        'mypage_top_service',
        'entry_flag',
        'end_time_v2',
        'exhibition_sso_flag',
    ];

    private $status_name = [1 => "開催前", 2 => "開催中", 3 => "終了"];

    public function exhibitionDates()
    {
        return $this->hasMany('App\Models\ExhibitionDate')->orderBy('day');
    }

    public function exhibitionGroup()
    {
        return $this->belongsTo('App\Models\ExhibitionGroup', 'exhibition_group_id', 'exhibition_group_id');
    }

    public function getStatusNameAttribute()
    {
        return $this->status_name[$this->status_code];
    }

    public function getOpenDateTimeAttribute()
    {
        $first = $this->exhibitionDates->sortBy('day')->first();

        return $first->day . " " . $first->open_time;
    }

    public function getEndDateTimeAttribute()
    {
        $first = $this->exhibitionDates->sortByDesc('day')->first();

        return $first->day . " " . $first->end_time;
    }

    public function getStatusCodeAttribute()
    {
        if ($this->isBefore()) {
            return 1;
        } elseif ($this->isHolding()) {
            return 2;
        } elseif ($this->isAfter()) {
            return 3;
        }

        return 99;
    }

    /**
     * 開催前か
     *
     * @return bool
     */
    public function isBefore()
    {
        $t = time();
        if ($t < strtotime($this->open_datetime)) {
            return true;
        }

        return false;
    }

    /**
     * 現在開催中か
     *
     * @return bool
     */
    public function isHolding()
    {
        $t = time();

        if (strtotime($this->open_datetime) <= $t && $t <= strtotime($this->end_datetime)) {
            return true;
        }

        return false;
    }

    /**
     * 開催後か
     *
     * @return bool
     */
    public function isAfter()
    {
        $t = time();

        if (strtotime($this->end_datetime) < $t) {
            return true;
        }

        return false;
    }
}
