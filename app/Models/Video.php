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
        
        if (!$this->processed) return "PROCESSING";

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
    

    public function longDuration() {
        $duration = $this->length;
        $hours = floor($duration / 3600);
        $minutes = floor(($duration - ($hours * 3600)) / 60);
        $seconds = $duration - ($hours * 3600) - ($minutes * 60);
        if ($hours > 0) {
            return $hours . ":" . $minutes . ":" . $seconds;
        } else if ($seconds > 0) {
            return $minutes . ":" . $seconds;
        } else {
            return "00:00";
        }
    }

    public function isFromYoutube() {
        return $this->youtube_id !== null;
    }

    public function html() {
        return '<video-grid-item class="' . ($this->youtube_id ? "youtube" : "") . '">
        <a href="/laravel/my-media-content-kingdom/public/watch?id=' . $this->getId() . '">
            <div class="img-wrapper">
                <img src="/laravel/my-media-content-kingdom/public/api/thumbnail?id=' . $this->thumbnail_id . '" class="video-thumbnail" style="width: 100%;">
                <p class="tag">' . $this->shortDuration() . '</p>
            </div>
            <div class="info">
                <p class="title mb-2">' . $this->title . '</p>
                <div class="d-flex justify-content-between mt-auto">
                    <div>
                        <div>' . $this->owner->name . '</div>
                        <div> ' . $this->getViews() . ' ' . 
                        ( $this->getViews() === 1 ? "view" : "views" ) . 
                        ' • ' . $this->getTimeAgo() . ' ' . ( $this->isNew() ? '<div class="new-badge">NEW</div>' : "" ) . '</div>
                    </div>
                </div>
            </div>
        </a>
    </video-grid-item>';
    }
}
