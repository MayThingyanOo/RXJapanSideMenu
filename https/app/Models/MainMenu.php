<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainMenu extends Model
{
    use HasFactory;
    protected $primaryKey = 'main_menu_id';

    protected $fillable = [
        'exhibition_id',
        'name',
        'color',
        'language_ja_flag',
        'orderBy'
    ];
}
