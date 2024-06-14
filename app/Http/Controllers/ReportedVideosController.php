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

    public function handleReport(Request $request) {
        $video = Video::findOrFail(VideoController::alphaID($request->input("id"), true));
        $report = VideoReport::where('video_id', $video->id);
        
        $action = $request->input('action');

        if ($action == "accept") {
            $video->terminated = 1;
            $video->terminated_at = now();
            $video->save();
            
            $report->delete();
        } elseif ($action == "deny") {
            $report->delete();
        }

        return redirect()->back();
    }
}
