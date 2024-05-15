<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Video extends Model
{
    use HasFactory;

    public function getId(): string {
        return base_convert($this->id, 10, 35);
    }

    // TODO - Add user class to link relations
    public function user(): HasOne {
        return $this->hasOne(User::class, "id", "owner_id");
    }

    // TODO - Add thumbnail class to link relations
    public function thumbnail(): HasOne {
        return $this->hasOne(Thumbnail::class, "id", "thumbnail_id");
    }
}
