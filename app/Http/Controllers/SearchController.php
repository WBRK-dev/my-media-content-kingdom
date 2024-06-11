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
        $filter = $request->input('filter') ?? "created_at";
        $direction = $request->input('sort') ?? "desc";
        $videos = Video::select("videos.*", DB::raw("SUM(video_likes.liked) as likes"), DB::raw("SUM(video_views.amount) as views"))
            ->leftJoin('video_likes', 'videos.id', '=', 'video_likes.video_id')
            ->leftJoin('video_views', 'videos.id', '=', 'video_views.video_id')
            ->groupBy("videos.id");
                
        foreach ($q as $word) {
            $videos->orWhere('title', 'LIKE', "%$word%");
        }

        $videos = $videos->orderBy($filter, $direction)->get();
        return view('search', [
            'videos' => $videos
        ]);
    }
}
