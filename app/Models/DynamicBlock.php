<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicBlock extends Model
{
    protected $fillable = ['title', 'video_url', 'content'];

}
