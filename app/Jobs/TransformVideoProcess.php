<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Storage;

use App\Models\Thumbnail;
use App\Models\Video;
use App\Models\VideoSegment;
use App\Models\VideoUploadJob;

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
    
        // Store segments and segment manifests
        try {

            $ffmpegBin = env("FFMPEG_BINARY", "ffmpeg");

            $thumbnail = new Thumbnail();
            $thumbnail->data = $this->videoData["thumbnail"] ? Storage::read($this->videoData["thumbnail"]) : "";
            $thumbnail->video_id = 0;
            $thumbnail->save();

            $video = new Video();
            $video->title = $this->videoData["title"] ?? "Untitled";
            $video->description = $this->videoData["description"];
            $video->owner_id = $this->videoData["userId"];
            $video->thumbnail_id = $thumbnail->id;
            $video->public = $this->videoData["visibility"];
            
            try {
                $output = shell_exec("$ffmpegBin -i " . $fullPath . DIRECTORY_SEPARATOR . "input.mp4 2>&1");
                $durationArray = explode(":", explode(".", explode(",", explode("Duration: ", $output)[1])[0])[0]);
                $video->length = $durationArray[0] * 3600 + $durationArray[1] * 60 + $durationArray[2];
            } catch (\Throwable $th) {
                Log::critical($th);
                $video->length = 0;
            }
            
            $video->save();

            $thumbnail->video_id = $video->id;
            $thumbnail->save();

            $videoPlaylist = new VideoSegment();
            $videoPlaylist->video_id = $video->id;
            $videoPlaylist->video_res = "";
            $videoPlaylist->file_name = "playlist-index.m3u8";
            $videoPlaylist->data = '#EXTM3U';
            $videoPlaylist->save();

            $videoUploadJob = new VideoUploadJob();
            $videoUploadJob->video_id = $video->id;
            $videoUploadJob->save();

            $resolutions = ["640x360", "1280x720", "1920x1080"];

            $thumbnailVideoSegmentIndex = null;

            foreach ($resolutions as $res) {

                try {
                    
                    $yRes = explode("x", $res)[1];

                    shell_exec("$ffmpegBin -i " . $fullPath . DIRECTORY_SEPARATOR . "input.mp4 -profile:v baseline -level 3.0 -s $res -start_number 0 -hls_time 10 -hls_list_size 0 -f hls " . $fullPath . DIRECTORY_SEPARATOR . $yRes . DIRECTORY_SEPARATOR . "index.m3u8");
                    
                    $files = Storage::files($this->dirUuid . "/$yRes");
                    foreach ($files as $file) {
                        $segment = new VideoSegment();
                        $segment->video_id = $video->id;
                        $segment->video_res = $yRes;

                        $explodedTitle = explode("/", $file);
                        $segment->file_name = $explodedTitle[count($explodedTitle) - 1];

                        $segment->data = Storage::read($file);

                        $segment->save();
                    }

                    if ($yRes === "360") {
                        $video->processed = 1;
                        $videoPlaylist->data = '#EXTM3U
    #EXT-X-STREAM-INF:PROGRAM-ID=1,RESOLUTION=640x360,NAME="360"
    360/index.m3u8';
                    } else if ($yRes === "720") {
                        $video->processed = 1;
                        $videoPlaylist->data = '#EXTM3U
    #EXT-X-STREAM-INF:PROGRAM-ID=1,RESOLUTION=640x360,NAME="360"
    360/index.m3u8
    #EXT-X-STREAM-INF:PROGRAM-ID=1,RESOLUTION=1280x720,NAME="720"
    720/index.m3u8';
                    } else if ($yRes === "1080") {
                        $video->processed = 1;
                        $videoPlaylist->data = '#EXTM3U
    #EXT-X-STREAM-INF:PROGRAM-ID=1,RESOLUTION=640x360,NAME="360"
    360/index.m3u8
    #EXT-X-STREAM-INF:PROGRAM-ID=1,RESOLUTION=1280x720,NAME="720"
    720/index.m3u8
    #EXT-X-STREAM-INF:PROGRAM-ID=1,RESOLUTION=1920x1080,NAME="1080"
    1080/index.m3u8';
                    }

                    if (!isset($this->videoData["thumbnail"])) {
                        $files = Storage::files($this->dirUuid . DIRECTORY_SEPARATOR . $yRes);
                        if (!$thumbnailVideoSegmentIndex) $thumbnailVideoSegmentIndex = rand(0, count($files) - 1);
                        shell_exec("$ffmpegBin -i " . storage_path("app") . DIRECTORY_SEPARATOR . $files[$thumbnailVideoSegmentIndex] . " -ss 00:00:05 -vframes 1 " . $fullPath . DIRECTORY_SEPARATOR . "thumbnail-$yRes.jpg");
                        $thumbnail->data = File::get($fullPath . DIRECTORY_SEPARATOR . "thumbnail-$yRes.jpg");
                    }

                    $videoUploadJob->status++;

                    $thumbnail->save();
                    $video->save();
                    $videoPlaylist->save();
                    $videoUploadJob->save();

                } catch (\Throwable $th) { Log::critical($th); }

            }

            $video->processed = 2;
            $video->save();

        } catch (\Throwable $th) { Log::critical($th); }

        // Cleanup
        Storage::deleteDirectory($this->dirUuid);
    }
}
