<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use willvincent\Rateable\Rateable;

class Post extends Model
{
    use Rateable;

    protected $fillable = [
        'title',
        'content'
    ];
}