<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = explode(" ", $request->input('q'));
        // $videos = Video::where("title", "LIKE", "%downfall%")->orderBy("created_at", "desc")->limit(50)->get();
        $videos = Video::select("*");
        foreach ($q as $word) {
            $videos->orWhere('title', 'LIKE', "%$word%");
        }
        $videos = $videos->get();
        return view('search', [
          'videos' => $videos
        ]);
    }
}
