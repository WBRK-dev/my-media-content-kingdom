<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use App\Models\Video;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, User $channel)
    {
        $data = [];
        $allowedPages = ["home", "videos"];
        $page = $request->input("page") ?? "home";
        if (in_array($page, $allowedPages) === false) return abort(404);

        if ($page === "home") {
            $data["most_liked"] = Video::select("videos.*", DB::raw("SUM(video_likes.liked) as likes"))
                ->leftJoin('video_likes', 'videos.id', '=', 'video_likes.video_id')
                ->where("owner_id", $channel->id)
                ->groupBy("id")->orderBy("likes", "desc")->orderBy("created_at", "desc")->limit(10)->get();
            $data["most_viewed"] = Video::select("videos.*", DB::raw("SUM(video_views.amount) as views"))
                ->leftJoin('video_views', 'videos.id', '=', 'video_views.video_id')
                ->where("owner_id", $channel->id)
                ->groupBy("id")->orderBy("views", "desc")->orderBy("created_at", "desc")->limit(10)->get();
        }

        return view("channel.$page", [
            'channel' => $channel,
            'page' => $page,
            "data" => $data
        ]);
    }

    /**
     * Show a uploading from a user.
     */
    function showPicture(Request $request) {
        // https://img.freepik.com/free-vector/flat-distorted-checkered-background_23-2148964042.jpg - banner
        // https://www.publicdomainpictures.net/pictures/70000/nahled/checks-plaid-background-blue.jpg - profile
        $allowedTypes = ["banner", "profile"];
        $channel = User::findOrFail($request->input("id"));
        $pictureType = $request->input("type") ?? "banner";
        if (!in_array($pictureType, $allowedTypes)) return abort(404);

        return $channel->getPicture($pictureType) ?? ( $pictureType === "banner" ? readfile(base_path() . "/public/banner.avif") : readfile(base_path() . "/public/profile.jpg") );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
