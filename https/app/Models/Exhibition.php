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

    protected $graph_data_appends = ['date_list', 'fetched_time', 'entry_count', 'exit_count', 'spot_ranking', 'graph_data'];

    private $date_list;
    private $status_name = [1 => "開催前", 2 => "開催中", 3 => "終了"];

    public function exhibition_dates()
    {
        return $this->hasMany('App\Models\ExhibitionDate')->orderBy('day');
    }

    public function exhibition_group()
    {
        return $this->belongsTo('App\Models\ExhibitionGroup', 'exhibition_group_id', 'exhibition_group_id');
    }
}
