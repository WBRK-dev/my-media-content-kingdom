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

        if ($filter == "release-date" && $direction == "desc") {
            $videos->orderBy("created_at", "desc");
        } else if ($filter == "length" && $direction == "desc") {
            $videos->orderBy("length", "desc");
        } else if ($filter == "views" && $direction == "desc") {
            $videos->orderBy("views", "desc");
        } else if ($filter == "likes" && $direction == "desc") {
            $videos->orderBy("likes", "desc");
        } else if ($filter == "release-date" && $direction == "asc") {
            $videos->orderBy("created_at", "asc");
        } else if ($filter == "length" && $direction == "asc") {
            $videos->orderBy("length", "asc");
        } else if ($filter == "views" && $direction == "asc") {
            $videos->orderBy("views", "asc");
        } else if ($filter == "likes" && $direction == "asc") {
            $videos->orderBy("likes", "asc");
        }

        $videos = $videos->distinct()->get();
        return view('search', [
            'videos' => $videos
        ]);
    }
}
