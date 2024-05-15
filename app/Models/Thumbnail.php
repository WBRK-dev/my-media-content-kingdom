<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Thumbnail extends Model
{
    use HasFactory;

    public function video(): HasOne {
        return $this->hasOne(Video::class, "video_id");
    }

    public function toBase64(): string {
        return base64_encode($this->data);
    }

}
