<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = explode(" ", $request->input('q'));
        $filter = $request->input('filter');
        $direction = $request->input('sort');
        $videos = Video::select('videos.*', 'video_likes.liked as likes', 'video_views.amount as views')
            ->leftJoin('video_likes', 'videos.id', '=', 'video_likes.video_id')
            ->leftJoin('video_views', 'videos.id', '=', 'video_views.video_id')
            ->groupBy('videos.created_at', 'video_likes.video_id', 'video_views.amount', 'videos.id', 'videos.title', 'videos.thumbnail_id', 'videos.owner_id', 'videos.public', 'videos.updated_at', 'video_likes.liked', 'videos.length', 'videos.processed', 'videos.terminated', 'videos.terminated_at');
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
        $videos = $videos->distinct()->get();
        return view('search', [
            'videos' => $videos
        ]);
    }
}
