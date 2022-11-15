<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    use HasFactory;
    protected $primaryKey = 'footer_id';

    protected $fillable = [
        'exhibition_id',
        'email',
        'phone_number',
        'start_time',
        'end_time',
    ];
}
