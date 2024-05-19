<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class WatchController extends Controller
{
    function show(Request $request) {
        return view('watch', [
            'video' => Video::findOrFail(VideoController::alphaID($request->input("id"), true))
        ]);
    }
}
