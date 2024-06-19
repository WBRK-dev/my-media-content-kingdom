<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

use App\Models\Video;
use App\Models\VideoSegment;
use App\Jobs\TransformVideoProcess;
use App\Jobs\TransformVideoFromYoutubeProcess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VideoController extends Controller
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
        return view("upload");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $uuid = Uuid::uuid4();

        $request->file("video")->storeAs($uuid, "input.mp4");
        $thumbnail = null;
        if ($request->hasFile("image")) $thumbnail = $request->file("image")->storeAs($uuid, "thumbnail." . $request->file("image")->extension());

        TransformVideoProcess::dispatchAfterResponse($uuid, [
            "title" => $request->input("title"),
            "thumbnail" => $thumbnail,
            "visibility" => $request->input("visibility") === "private" ? false : true,
            "userId" => Auth::user()->id
        ]);
        
        return back();
    }

    /**
     * Display the video resource.
     */
    public function show(string $videoId, string $resolution, string $fileName)
    {
        return VideoSegment::where("video_id", $videoId)->where("video_res", $resolution)->where("file_name", $fileName)->first()?->data;
    }

    /**
     * Display the video playlist resource.
     */
    public function showPlaylist(string $videoId, string $fileName)
    {
        return VideoSegment::where("video_id", $videoId)->where("file_name", "playlist-" . $fileName)->first()?->data;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $video = Video::findOrFail(VideoController::alphaID($request->input("id"), true));
        $video->delete();
        return back();
    }

    /**
     * Store a resource from youtube in storage.
     */
    public function storeFromYoutube(Request $request)
    {
        $uuid = Uuid::uuid4();
        Storage::disk("local")->makeDirectory($uuid);

        $data = null; $videoFormat = null; $audioFormat = null; $thumbnail = null;
        try {
            $data = Http::withHeaders([
                "Host"=>"www.youtube.com",
                "Content-Type"=>"application/json",
                "User-Agent"=>"com.google.android.youtube/17.10.35 (Linux; U; Android 12; GB) gzip",
                "Accept"=>"*/*",
                "Origin"=>"https://www.youtube.com",
                "Referer"=>"https://www.youtube.com/",
                "Accept-Encoding"=>"gzip, deflate",
                "Accept-Language"=>"de,de-DE;q=0.9,en;q=0.8,en-GB;q=0.7,en-US;q=0.6",
            ])->post("https://www.youtube.com/youtubei/v1/player?key=AIzaSyA8eiZmM1FaDVjRy-df2KTyQ_vz_yYM39w", [
                "context"=>[
                    "client"=>[
                        "hl"=>"en",
                        "gl"=>"US",
                        "clientName"=>"ANDROID",
                        "clientVersion"=>"17.36.4",
                        "androidSdkVersion"=>"31"
                    ]
                ],
                "videoId"=>$request->input("youtubeid"),
                "racyCheckOk"=>true,
                "contentCheckOk"=>true
            ]);
    
            $videoFormats = array_values(array_filter($data->json()["streamingData"]["adaptiveFormats"], function($format) {
                return str_contains($format["mimeType"], "video/mp4");
            }));
            $audioFormats = array_values(array_filter($data->json()["streamingData"]["adaptiveFormats"], function($format) {
                return str_contains($format["mimeType"], "audio/mp4") && ( $format["audioQuality"] === "AUDIO_QUALITY_MEDIUM" || $format["audioQuality"] === "AUDIO_QUALITY_HIGH" );
            }));
            $thumbnails = $data->json()["videoDetails"]["thumbnail"]["thumbnails"];
            $thumbnailHeights = array_map(function($thumbnail) {
                return $thumbnail["height"];
            }, $thumbnails);
    
            for ($i=0; $i < count($videoFormats); $i++) { 
                if ($videoFormats[$i]["height"] <= 1080) {
                    $videoFormat = $videoFormats[$i];
                    break;
                }
            }
            $audioFormat = $audioFormats[0] ?? null;
    
            try {
                $thumbnail = $thumbnails[array_search(max($thumbnailHeights), $thumbnailHeights)]["url"];
            } catch (\Throwable $th) { Log::error("Unable to find thumbnail."); }
        } catch (\Throwable $th) { Log::error("Something went wrong while getting video, audio and thumbnail from youtube."); Log::critical($th); }

        if ($videoFormat === null || $audioFormat === null || $thumbnail === null) { 
            Log::error("A video or audio format could not be found. Logs can be found in \"$uuid\""); 
            Storage::write($uuid . DIRECTORY_SEPARATOR . "youtube-api-response.json", $data->body());
            return abort(500);
        }

        TransformVideoFromYoutubeProcess::dispatchAfterResponse($uuid, [
            "title" =>      $data->json()["videoDetails"]["title"] . " - " . $data->json()["videoDetails"]["author"],
            "thumbnail" =>  $thumbnail,
            "visibility" => $request->input("visibility") === "private" ? false : true,
            "userId" =>     Auth::user()->id,
            "video" =>      $videoFormat["url"],
            "audio" =>      $audioFormat["url"],
            "youtubeId" =>  $request->input("youtubeid")
        ]);

        return back();
    }

   /*
    * @author  Kevin van Zonneveld <kevin@vanzonneveld.net>
    * @author  Simon Franz
    * @author  Deadfish
    * @author  SK83RJOSH
    * @copyright 2008 Kevin van Zonneveld (https://kevin.vanzonneveld.net)
    * @license   https://www.opensource.org/licenses/bsd-license.php New BSD Licence
    * @version   SVN: Release: $Id: alphaID.inc.php 344 2009-06-10 17:43:59Z kevin $
    * @link    https://kevin.vanzonneveld.net/
    *
    * @param mixed   $in   String or long input to translate
    * @param boolean $to_num  Reverses translation when true
    * @param mixed   $pad_up  Number or boolean padds the result up to a specified length
    * @param string  $pass_key Supplying a password makes it harder to calculate the original ID
    *
    * @return mixed string or long
    */
    public static function alphaID($in, $to_num = false, $pad_up = false, $pass_key = null)
    {
        $out   = '';
        $index = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $base  = strlen($index);
    
        if ($pass_key !== null) {
            // Although this function's purpose is to just make the
            // ID short - and not so much secure,
            // with this patch by Simon Franz (https://blog.snaky.org/)
            // you can optionally supply a password to make it harder
            // to calculate the corresponding numeric ID
        
            for ($n = 0; $n < strlen($index); $n++) {
                $i[] = substr($index, $n, 1);
            }
        
            $pass_hash = hash('sha256',$pass_key);
            $pass_hash = (strlen($pass_hash) < strlen($index) ? hash('sha512', $pass_key) : $pass_hash);
        
            for ($n = 0; $n < strlen($index); $n++) {
                $p[] =  substr($pass_hash, $n, 1);
            }
        
            array_multisort($p, SORT_DESC, $i);
            $index = implode($i);
        }
    
        if ($to_num) {
            // Digital number  <<--  alphabet letter code
            $len = strlen($in) - 1;
        
            for ($t = $len; $t >= 0; $t--) {
                $bcp = pow($base, $len - $t);
                $out = ( (int) $out) + strpos($index, substr($in, $t, 1)) * $bcp;
            }
        
            if (is_numeric($pad_up)) {
                $pad_up--;
        
                if ($pad_up > 0) {
                $out -= pow($base, $pad_up);
                }
            }
        } else {
            // Digital number  -->>  alphabet letter code
            if (is_numeric($pad_up)) {
                $pad_up--;
        
                if ($pad_up > 0) {
                $in += pow($base, $pad_up);
                }
            }
        
            for ($t = ($in != 0 ? floor(log($in, $base)) : 0); $t >= 0; $t--) {
                $bcp = pow($base, $t);
                $a   = floor($in / $bcp) % $base;
                $out = $out . substr($index, $a, 1);
                $in  = $in - ($a * $bcp);
            }
        }
    
        return $out;
    }
}
