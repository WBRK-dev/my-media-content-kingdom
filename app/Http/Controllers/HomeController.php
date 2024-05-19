<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    public function show() {
        $videos = Video::orderBy("created_at", "desc")->limit(50)->get();
        return view('home', [
          'videos' => $videos
        ]);
      }
}
