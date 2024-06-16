<div class="upload-popup-wrapper" id="upload-popup" data-page="1">

    <form action="{{config("app.url")}}/upload" method="post" enctype="multipart/form-data">

        @csrf
    
        <div class="upload-popup">
        
            <div class="upload-popup-header">
                <p>Upload Video</p>
                <button type="button" onclick="toggleUploadPopup()"><i class="fi fi-sr-cross"></i></button>
            </div>

            <div class="upload-popup-pages">

                <div class="upload-popup-page upload-popup-page-ontop" data-page="1" style="display: grid; grid-template-rows: 1fr 1fr; gap: 1rem;">
                    <button type="button" onclick="uploadPopupOpenFileDialog('video')" class="d-flex align-items-center justify-content-center" style="all: unset; border: 4px dashed #00b4d8; border-radius: 1rem; cursor: pointer;">
                        
                        <div class="d-flex flex-column align-items-center gap-2">
                            <i class="fi fi-sr-upload" style="font-size: 40px;"></i>
                            <p class="fs-4" id="video-title">Select a video to upload!</p>
                        </div>

                    </button>
                    <div class="d-flex align-items-center justify-content-center" style="border: 4px dashed #00b4d8; border-radius: 1rem;">
                        <div class="d-flex flex-column gap-2 align-items-center">
                            <p class="fs-5">Upload with youtube video id.</p>
                            <input type="text" class="upload-popup-input" style="width: 200px;" id="youtube-id">
                            <button type="button" onclick="uploadPopupSubmitFromYoutube()" class="upload-popup-button">Upload</button>
                        </div>
                    </div>
                </div>

                <div class="upload-popup-page upload-popup-page-next" data-page="2" style="display: grid; grid-template-rows: 1fr auto; gap: 1rem;">
                    <button type="button" onclick="uploadPopupOpenFileDialog('thumbnail')" class="d-flex align-items-center justify-content-center" style="all: unset; border: 4px dashed #00b4d8; border-radius: 1rem; cursor: pointer;">
                        
                        <div class="d-flex flex-column align-items-center gap-2">
                            <i class="fi fi-sr-upload" style="font-size: 40px;"></i>
                            <p class="fs-4" id="thumbnail-title">Select a thumbnail to upload!</p>
                        </div>

                    </button>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <button type="button" onclick="prevUploadPopupPage()" class="upload-popup-button upload-popup-button-secondary"> <i class="fi fi-sr-left"></i> Previous</button>
                        <button type="button" onclick="nextUploadPopupPage()" class="upload-popup-button upload-popup-button-secondary">Skip<i class="fi fi-sr-right"></i></button>
                    </div>
                </div>

                <div class="upload-popup-page" data-page="3" style="display: grid; grid-template-rows: 1fr auto; gap: 1rem;">
                    <div>
                        <p class="fs-5">Video Title:</p>
                        <input type="text" class="upload-popup-input" name="title" required>
                        <p class="fs-5 mt-2">Visibility</p>
                        <input type="radio" name="visibility" value="public" id="visibilitypublic" checked><label class="ms-1" for="visibilitypublic">Public</label><br>
                        <input type="radio" name="visibility" value="private" id="visibilityprivate"><label class="ms-1" for="visibilityprivate">Private</label>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <button type="button" onclick="prevUploadPopupPage()" class="upload-popup-button upload-popup-button-secondary"> <i class="fi fi-sr-left"></i> Previous</button>
                        <button type="submit" class="upload-popup-button">Upload<i class="fi fi-sr-up"></i></button>
                    </div>
                </div>

            </div>

        </div>

        <input type="file" name="video" id="video" onchange="uploadPopupFileChange(this, 'video')" accept="video/*" style="display: none;">
        <input type="file" name="image" id="thumbnail" onchange="uploadPopupFileChange(this, 'thumbnail')" accept="image/*" style="display: none;">
    
    </form>

    <form action="{{config("app.url")}}/upload-youtube" method="post" id="youtube-form">
        @csrf
        <input type="hidden" name="youtubeid" id="youtube">
        <input type="hidden" name="visibility" value="public">
    </form>

</div>