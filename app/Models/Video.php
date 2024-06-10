<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\VideoController;
use Illuminate\Database\Eloquent\Relations\HasOne;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Video extends Model
{
    use HasFactory;

    public function getId(): string {
        return VideoController::alphaId($this->id);
    }

    public function owner(): HasOne {
        return $this->hasOne(User::class, "id", "owner_id");
    }

    public function thumbnail(): HasOne {
        return $this->hasOne(Thumbnail::class, "id", "thumbnail_id");
    }

    public function likes(): HasMany {
        return $this->hasMany(VideoLike::class, "video_id", "id");
    }

    public function getLikes() {
        $likeRows = $this->hasMany(VideoLike::class)->get();
        $likeAmount = 0;
        $likeAmount = $likeRows->filter(function($row) {
            return $row->liked == 1;
        })->count();
        return $likeAmount;
    }

    public function getDislikes() {
        $dislikeRows = $this->hasMany(VideoLike::class)->get();
        $dislikeAmount = 0;
        $dislikeAmount = $dislikeRows->filter(function($row) {
            return $row->liked == 0;
        })->count();
        return $dislikeAmount;
    }

    public function getViews() {
        $viewRows = $this->hasMany(VideoView::class)->get();
        $viewAmount = 0;
        foreach ($viewRows as $row) {
            $viewAmount += $row->amount;
        }
        return $viewAmount;
    }

    public function getTimeAgo() {
        $createdAt = $this->created_at;
        $timeAgo = Carbon::parse($createdAt)->diffForHumans();
        return $timeAgo;
    }

    public function isNew() {
        return $this->created_at->gt(Carbon::now()->subDays(4));
    }

    public function shortDuration() {
        $duration = $this->length;
        $hours = floor($duration / 3600);
        $minutes = floor(($duration - ($hours * 3600)) / 60);
        $seconds = $duration - ($hours * 3600) - ($minutes * 60);
        if ($hours > 0) {
            return $hours . "h";
        } else if ($minutes > 0) {
            return $minutes . "m";
        } else if ($seconds > 0) {
            return $seconds . "s";
        } else {
            return "0s";
        }
    }

    public function isFromYoutube() {
        return $this->youtube_id !== null;
    }
}
