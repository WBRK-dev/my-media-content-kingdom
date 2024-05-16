<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Storage;

use App\Models\Thumbnail;
use App\Models\Video;
use App\Models\VideoSegment;
use App\Models\VideoSegmentManifest;

class TransformVideoProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $dir = "";
    private $dirUuid = "";
    private $videoData = [];

    /**
     * Create a new job instance.
     */
    public function __construct($dir, $video)
    {
        $this->dirUuid = $dir;
        $this->dir = storage_path("app") . DIRECTORY_SEPARATOR . $dir;
        $this->videoData = $video;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Initializing directories
        Storage::disk("local")->makeDirectory($this->dirUuid."/360");
        Storage::disk("local")->makeDirectory($this->dirUuid."/720");
        Storage::disk("local")->makeDirectory($this->dirUuid."/1080");
        $fullPath = $this->dir;

        try {
            // Execute transcoding commands using ffmpeg
            shell_exec("ffmpeg -i " . $fullPath . DIRECTORY_SEPARATOR . "input.mp4 -profile:v baseline -level 3.0 -s 1920x1080 -start_number 0 -hls_time 10 -hls_list_size 0 -f hls " . $fullPath . DIRECTORY_SEPARATOR . "1080" . DIRECTORY_SEPARATOR . "index.m3u8");
            shell_exec("ffmpeg -i " . $fullPath . DIRECTORY_SEPARATOR . "input.mp4 -profile:v baseline -level 3.0 -s 1280x720 -start_number 0 -hls_time 10 -hls_list_size 0 -f hls " . $fullPath . DIRECTORY_SEPARATOR . "720" . DIRECTORY_SEPARATOR . "index.m3u8");
            shell_exec("ffmpeg -i " . $fullPath . DIRECTORY_SEPARATOR . "input.mp4 -profile:v baseline -level 3.0 -s 640x360 -start_number 0 -hls_time 10 -hls_list_size 0 -f hls " . $fullPath . DIRECTORY_SEPARATOR . "360" . DIRECTORY_SEPARATOR . "index.m3u8");
        } catch (\Throwable $th) { Log::critical($th); }
    
        // Store segments and segment manifests
        try {

            // Log::debug($this->videoData["thumbnail"]);
            
            $thumbnail = new Thumbnail();
            $thumbnail->data = Storage::read($this->videoData["thumbnail"]);
            $thumbnail->video_id = 0;
            $thumbnail->save();

            $video = new Video();
            $video->title = $this->videoData["title"] ?? "Untitled";
            $video->owner_id = 1;
            $video->thumbnail_id = $thumbnail->id;
            $video->public = $this->videoData["visibility"];
            $video->save();

            $thumbnail->video_id = $video->id;
            $thumbnail->save();

            $resolutions = ["360", "720", "1080"];

            foreach ($resolutions as $res) {
                
                $files = Storage::files($this->dirUuid . "/$res");
                foreach ($files as $file) {
                    $segment = new VideoSegment();
                    $segment->video_id = $video->id;
                    $segment->video_res = $res;

                    $explodedTitle = explode("/", $file);
                    $segment->file_name = $explodedTitle[count($explodedTitle) - 1];

                    $segment->data = Storage::read($file);

                    $segment->save();
                }

            }

            VideoSegment::insert([
                "video_id" => $video->id,
                "video_res" => "",
                "file_name" => "playlist-index.m3u8",
                "data" => '#EXTM3U
#EXT-X-STREAM-INF:PROGRAM-ID=1,RESOLUTION=1280x720,NAME="720"
720/index.m3u8
#EXT-X-STREAM-INF:PROGRAM-ID=1,RESOLUTION=640x360,NAME="360"
360/index.m3u8
#EXT-X-STREAM-INF:PROGRAM-ID=1,RESOLUTION=1920x1080,NAME="1080"
1080/index.m3u8'
            ]);


        } catch (\Throwable $th) { Log::critical($th); }

        // Cleanup
        Storage::deleteDirectory($this->dirUuid);
    }
}
