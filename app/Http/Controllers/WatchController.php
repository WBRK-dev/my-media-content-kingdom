<?php

namespace App\Http\Controllers;

use App\Models\Video;
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
            ($table->where('user_id', 2)->where('video_id', $video->id)->where('amount', '<', 10)->increment('amount', 1));
        } 
        return view('watch', [
            'video' => $video
        ]);
    }
}
