<?php

namespace App\Models;

class ExhibitionGroup extends BaseModel
{
    protected $connection = 'qpass';
    protected $softDeletes = true;
    protected $table = 'exhibition_groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'group_name', 'note', 'order'];

    public function exhibitions()
    {
        return $this->hasMany('App\Models\Exhibition');
    }
}
