<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoReport;
use Illuminate\Http\Request;

class ReportedVideosController extends Controller
{
    public function show() {
        $videos = VideoReport::get();
        return view('reported-videos', [
            'videos' => $videos
        ]);
    }

    public function terminateVideo(Request $request) {
        $video = Video::findOrFail(VideoController::alphaID($request->input("id"), true));
        // if ($video->count()) {
        //     $video[0]->terminated = 1;
        //     $video[0]->terminated_at = now();
        // }
        $video->terminated = 1;
        $video->terminated_at = now();
        $video->save();
    }

    public function deleteReport(Request $request) {
        $video = Video::findOrFail(VideoController::alphaID($request->input("id"), true));
    }
}
