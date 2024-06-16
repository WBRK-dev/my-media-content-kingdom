<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPicture;
use Illuminate\Http\Request;

use App\Models\Video;
use Illuminate\Support\Facades\Auth;
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
        $isChannelOwner = Auth::check() && Auth::user()->id === $channel->id;
        $data = [];
        $allowedPages = ["home", "videos", "settings"];
        $page = $request->input("page") ?? "home";
        if (!in_array($page, $allowedPages)) return abort(404);
        if ($page === "settings" && !$isChannelOwner) return abort(403);

        if ($page === "home") {
            $data["most_liked"] = Video::select("videos.*", DB::raw("SUM(video_likes.liked) as likes"))
                ->leftJoin('video_likes', 'videos.id', '=', 'video_likes.video_id')
                ->where("owner_id", $channel->id)
                ->where("processed", true)->where("terminated", false)
                ->groupBy("id")->orderBy("likes", "desc")->orderBy("created_at", "desc")->limit(10);
                if (!$isChannelOwner) $data["most_liked"] = $data["most_liked"]->where("public", true)->get();
                else $data["most_liked"] = $data["most_liked"]->get();
            $data["most_viewed"] = Video::select("videos.*", DB::raw("SUM(video_views.amount) as views"))
                ->leftJoin('video_views', 'videos.id', '=', 'video_views.video_id')
                ->where("owner_id", $channel->id)
                ->where("processed", true)->where("terminated", false)
                ->groupBy("id")->orderBy("views", "desc")->orderBy("created_at", "desc")->limit(10);
                if (!$isChannelOwner) $data["most_viewed"] = $data["most_viewed"]->where("public", true)->get();
                else $data["most_viewed"] = $data["most_viewed"]->get();
        } else if ($page === "videos") {
            $data["videos"] = $channel->videos()->where("processed", true)->where("terminated", false);
            if (!$isChannelOwner) $data["videos"] = $data["videos"]->where("public", true)->orderBy("created_at", "desc")->paginate(25);
            else $data["videos"] = $data["videos"]->orderBy("created_at", "desc")->paginate(25);
            $data["hasNextPage"] = $data["videos"]->hasMorePages();
        }

        return view("channel.$page", [
            'channel' => $channel,
            'page' => $page,
            "data" => $data,
            "isChannelOwner" => $isChannelOwner
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

        return $channel->getPicture($pictureType)->first()?->data ?? ( $pictureType === "banner" ? readfile(base_path() . "/public/banner.avif") : readfile(base_path() . "/public/profile.jpg") );
    }

    /**
     * Show a list of videos from a user.
     */
    function showPaginatedVideos(Request $request) {
        $channel = User::findOrFail($request->input("id"));
        $isChannelOwner = Auth::check() && Auth::user()->id === $channel->id;
        
        $videos = $channel->videos()->where("processed", true)->where("terminated", false);

        if (!$isChannelOwner) $videos = $videos->where("public", true)->orderBy("created_at", "desc")->paginate(25);
        else $videos = $videos->orderBy("created_at", "desc")->paginate(25);

        $hasNextPage = $videos->hasMorePages();

        $generatedVideos = "";
        for ($i=0; $i < count($videos); $i++) { 
            $generatedVideos .= '<video-grid-item class="' . ($videos[$i]->youtube_id ? "youtube" : "") . '">
    <a href="/laravel/my-media-content-kingdom/public/watch?id=' . $videos[$i]->getId() . '">
        <div class="img-wrapper">
            <img src="/laravel/my-media-content-kingdom/public/api/thumbnail?id=' . $videos[$i]->thumbnail_id . '" class="video-thumbnail" style="width: 100%;">
            <p class="tag">' . $videos[$i]->shortDuration() . '</p>
        </div>
        <div class="info">
            <p class="title mb-2">' . $videos[$i]->title . '</p>
            <div class="d-flex justify-content-between mt-auto">
                <div>
                    <div>' . $videos[$i]->owner->name . '</div>
                    <div> ' . $videos[$i]->getViews() . ' ' . 
                    ( $videos[$i]->getViews() === 1 ? "view" : "views" ) . 
                    ' • ' . $videos[$i]->getTimeAgo() . ' ' . ( $videos[$i]->isNew() ? '<div class="new-badge">NEW</div>' : "" ) . '</div>
                </div>
            </div>
        </div>
    </a>
</video-grid-item>';
        }

        return response()->json([
            "videos" => $generatedVideos,
            "hasNextPage" => $hasNextPage
        ]);
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
    public function update(Request $request, User $channel)
    {
        $allowedTypes = ["name", "banner", "profile"];
        $type = $request->input("type");
        if (!in_array($type, $allowedTypes)) return abort(404);

        if ($type === "name") {
            $channel->name = $request->input("name");
            $channel->save();
        } else {
            $picture = $channel->getPicture($type)->first();
            if ($picture === null) $picture = new UserPicture();

            $picture->user_id = $channel->id;
            $picture->type = $type;
            $picture->data = $request->file("image")->get();
            $picture->save();
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
