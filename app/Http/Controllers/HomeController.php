<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    public function show() {
        $videos = Video::where("public", true)->where("processed", true)->where("terminated", false)->orderBy("created_at", "desc")->paginate(50);
        return view('home', [
            'videos' => $videos
        ]);
    }

    public function showPaginatedVideos(Request $request) {
        $videos = Video::where("public", true)->where("processed", true)->where("terminated", false)->orderBy("created_at", "desc")->paginate(50);
        $html = "";

        for ($i=0; $i < count($videos->items()); $i++) { 
            $html .= $videos[$i]->html();
        }

        return response()->json([
            'videos' => $html,
            "hasNextPage" => $videos->hasMorePages()
        ]);
    }
}
