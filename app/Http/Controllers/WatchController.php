<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoLike;
use App\Models\VideoReport;
use App\Models\VideoView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WatchController extends Controller
{
    function show(Request $request) {
        $video = Video::findOrFail(VideoController::alphaID($request->input("id"), true));
        $viewInput = new VideoView();
        $table = DB::table('video_views');
        if (!$table->where('user_id', 2)->where('video_id', $video->id)->exists()) {
            $viewInput->user_id = 2;
            $viewInput->video_id = $video->id;
            $viewInput->amount = 1;
            $viewInput->save();
        } else {
            ($table->where('user_id', 2)->where('video_id', $video->id)->where('amount', '<', 20)->increment('amount', 1));
        } 
        $videoLikes = $video->likes->where("user_id", 1);
        $likestatus = null;
        if ($videoLikes->count()) {
            $likestatus = $videoLikes[0]->liked;
        }
        
        return view('watch', [
            'video' => $video,
            'likestatus' => $likestatus
        ]);
    }

    function videoLiked(Request $request) {
        $video = Video::findOrFail(VideoController::alphaID($request->input("id"), true));
        $videoLikes = $video->likes->where("user_id", 1);
        if ($videoLikes->count()) {
            $videoLikes[0]->liked = 1;
            $videoLikes[0]->save();
        } else {
            VideoLike::insert([
                "user_id" => 1,
                "video_id" => $video->id,
                "liked" => 1,
            ]);
        }
    }

    function videoDisliked(Request $request) {
        $video = Video::findOrFail(VideoController::alphaID($request->input("id"), true));
        $videoLikes = $video->likes->where("user_id", 1);
        if ($videoLikes->count()) {
            $videoLikes[0]->liked = 0;
            $videoLikes[0]->save();
        } else {
            VideoLike::insert([
                "user_id" => 1,
                "video_id" => $video->id,
                "liked" => 0,
            ]);
        }
    }
 
    function deleteLikeRow(Request $request) {
        $video = Video::findOrFail(VideoController::alphaID($request->input("id"), true));
        $videoLikes = $video->likes->where("user_id", 1);
        if ($videoLikes->count()) {
            $videoLikes[0]->delete();
        }
    }

    function videoReported(Request $request) {
        $reportInput = new VideoReport();
        $table = DB::table('video_reports');
        if (!$table->where('user_id', 1)->where('video_id', $request->id)->where('reason_id', $request->reason_id)->exists()) {
            $reportInput->user_id = 1;
            $reportInput->video_id = $request->id;
            $reportInput->reason_id = $request->reason_id;
            $reportInput->save();
        }
    }
}
