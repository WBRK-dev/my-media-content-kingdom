<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = explode(" ", $request->input('q'));
        $filter = $request->input('filter');
        $direction = $request->input('sort');
        $videos = Video::select("videos.*", DB::raw("SUM(video_likes.liked) as likes"), DB::raw("SUM(video_views.amount) as views"))
            ->leftJoin('video_likes', 'videos.id', '=', 'video_likes.video_id')
            ->leftJoin('video_views', 'videos.id', '=', 'video_views.video_id')
            ->groupBy("videos.id");
                
        foreach ($q as $word) {
            $videos->orWhere('title', 'LIKE', "%$word%");
        }

        if ($filter == "release-date") {
            $videos->orderBy("created_at", $direction);
        } else if ($filter == "length") {
            $videos->orderBy("length", $direction);
        } else if ($filter == "views") {
            $videos->orderBy("views", $direction);
        } else if ($filter == "likes") {
            $videos->orderBy("likes", $direction);
        }

        $videos = $videos->get();
        return view('search', [
            'videos' => $videos
        ]);
    }
}
