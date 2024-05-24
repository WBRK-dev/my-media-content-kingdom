<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

use App\Models\Video;
use App\Models\VideoSegment;
use App\Jobs\TransformVideoProcess;

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
        $thumbnail = $request->file("image")->storeAs($uuid, "thumbnail." . $request->file("image")->extension());

        TransformVideoProcess::dispatchAfterResponse($uuid, [
            "title" => $request->input("title"),
            "thumbnail" => $thumbnail,
            "visibility" => $request->input("visibility") === "private" ? false : true,
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
    public function destroy(Video $video)
    {
        $video->delete();
        return redirect("/");
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
