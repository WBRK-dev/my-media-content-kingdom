<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VideoReport extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    public function video() : HasOne {
        return $this->hasOne(Video::class, "id", "video_id");
    }

    public function user() : HasOne {
        return $this->hasOne(User::class, "id", "user_id");
    }
}
