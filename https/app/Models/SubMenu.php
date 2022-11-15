<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubMenu extends Model
{
    use HasFactory;
    protected $primaryKey = 'sub_menu_id';

    protected $fillable = [
        'main_menu_id',
        'label',
        'link',
        'image_name',
    ];
}
