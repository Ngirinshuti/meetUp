<?php

require_once '../classes/init.php';

$msg = "";
$is_valid = false;

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    include_once "./Story.php";


    $has_no_video = empty($_FILES['video']['name']);
    $has_no_audio = empty($_FILES['audio']['name']);
    $has_no_media = $has_no_audio && $has_no_video;
    $has_no_image = empty($_FILES['image']['name']);
    if (!$has_no_video) {
        $description = $_POST['descriptionVideo'];
    } elseif (!$has_no_audio) {
        $description = $_POST['descriptionAudio'];
    } elseif (!$has_no_image) {
        $description = $_POST['descriptionPhoto'];
    } else {
        $description = $_POST['description'];
    }

    $has_no_file = $has_no_image && $has_no_media;
    $has_image_and_video = (!$has_no_image && !$has_no_video);
    $has_audio_and_video = !$has_no_audio && !$has_no_video;
    $has_no_desc = empty($description);
    $has_no_content = ($has_no_file && $has_no_desc);

    if ($has_no_content) {
        $msg = "You have to upoad an image, video, audio or write some text";
    } elseif ($has_image_and_video) {
        $msg = "You can't upload an image and video for the same story.";
    } elseif ($has_audio_and_video) {
        $msg = "You can't upload both audio and video, just choose one";
    } else {
        $image = uploadFile('image', './story_uploads');
        $media = uploadFile('video', './story_uploads') ?: uploadFile('audio', './story_uploads');

        $story = Story::create(
            username: $me->username,
            image: $image,
            description: $description,
            media: $media
        );

        $msg = "Story created successfully!";
        $is_valid = true;
        header("location: ./index.php?story={$story->id}");
        var_dump($_POST, $_FILES['image']);
        $msg = "Something went wrong!";
    }
}


/**
 * File upload function
 *
 * @param string  $field_name  name of form field containing the file
 * @param string  $destination file upload folder
 * @param boolean $uploaded    reference variable true if file was uploaded
 *
 * @return string uploaded file name
 */
function uploadFile(
    string $field_name,
    $destination = "./uploads",
    &$uploaded = false
): string {
    $random_number = strval(rand(100000, 9999999));
    $file_name = $random_number;
    $file_destination = $destination . "/$file_name";

    $uploaded = move_uploaded_file(
        $_FILES[$field_name]['tmp_name'],
        $file_destination
    );

    $file_name = basename(!$uploaded  ? "" : $file_destination);
    return $file_name;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./css/stories.css">
    <link rel="stylesheet" href="./css/form.css">
    <link rel="stylesheet" href="<?php echo getUrl("/css/create_story.css") ?>">
    <title>Create Story</title>
    <?php require_once __DIR__ . "/../lib/createEmiji.php"; ?>
</head>

<body>

    <div class="container">
        <?php require_once __DIR__ . "/../menu/menu.php"; ?>

        <div class="storyContainer ">
            <div class="formContainer">
                <h1 class="header">Create story</h1>
                <?php echo !empty($msg) ? "<p class='" . (!$is_valid ? 'errorMsg' : 'successMsg') . "'>$msg</p>" : "" ?>
                <p class="formText">Start sharing your moments and memories</p>

                <form action="story_create.php" method="post" enctype="multipart/form-data">
                    <div data-video-edit-modal class="profileEditModal hide">
                        <div class="pickerContainer">
                            <button type="button" data-pick-video>
                                <i class="fa fa-file-video-o"></i>
                                Choose video
                            </button>
                            <input accept="video/mp4,video/webm" type="file" name="video" id="video" data-edit-video-input autoplay="true" />
                            <div data-video-placeholder class="profilePhotoPlaceholder">
                            </div>
                            <div class="inputContainer">
                                <textarea name="descriptionVideo" class="storyCreateArea" id="desc" data-emojiable="true" data-emoji-input="unicode" id="enterText" placeholder="Add text..." style="border:20px"></textarea>
                            </div>
                            <div class="btns">
                                <button type="submit" data-edit-video-form-submit class="success">Create</button>
                                <button type="button" data-hide-edit-video-modal class="danger">Cancel</button>
                            </div>
                        </div>
                    </div>
                    <div data-audio-edit-modal class="profileEditModal hide">
                        <div class="pickerContainer">
                            <button type="button" data-pick-audio>
                                <i class="fa fa-file-audio-o"></i>
                                Choose Audio
                            </button>
                            <input accept="audio/*" type="file" name="audio" id="audio" data-edit-audio-input />
                            <div data-audio-placeholder class="profilePhotoPlaceholder">
                            </div>
                            <div class="inputContainer">
                                <textarea name="descriptionAudio" data-emojiable="true" data-emoji-input="unicode" id="enterText" class="storyCreateArea" id="desc" placeholder="Add text..." style="border:20px"></textarea>
                            </div>
                            <div class="btns">
                                <button type="submit" data-edit-audio-form-submit class="success">Create</button>
                                <button type="button" data-hide-edit-audio-modal class="danger">Cancel</button>
                            </div>
                        </div>
                    </div>
                    <div data-photo-edit-modal class="profileEditModal hide">
                        <div class="pickerContainer">

                            <button type="button" data-pick-photo>
                                <i class="fa fa-image"></i>
                                Choose photo
                            </button>
                            <input accept="image/*" type="file" name="image" data-edit-photo-input>
                            <div data-photo-placeholder class="profilePhotoPlaceholder">
                            </div>
                            <div class="inputContainer">
                                <textarea name="descriptionPhoto" class="storyCreateArea" id="desc" data-emojiable="true" data-emoji-input="unicode" id="enterText" placeholder="Add text..." style="border:20px"></textarea>
                            </div>
                            <div class="btns">
                                <button type="submit" data-edit-photo-form-submit class="success">Add Story</button>
                                <button type="button" data-hide-edit-photo-modal class="danger">Cancel</button>
                            </div>
                        </div>
                    </div>
                    <div class="">

                        <div class="storyImgEdit">

                            <button type="button" data-story-edit-icon_photo><i title="Add Image" class="fa fa-image fa-2x" id="icon" style=""></i></button>
                            <button type="button" data-story-edit-icon_video><i title="Add Video" class="fa fa-file-video-o fa-2x" aria-hidden="true" id="clip"></i></button>
                            <button type="button" data-story-edit-icon_audio><i class="fa fa-file-audio-o fa-2x" aria-hidden="true" id="sound" title="Add Audio"></i></button>
                        </div>
                    </div>
                    <div class="inputContainer">
                        <textarea class="storyCreateArea" id="desc" name="description" placeholder="Write you story here" data-emojiable="true" data-emoji-input="unicode" style="height: 1cm;"></textarea>
                    </div>
                    <button type="submit">Create story</button>
                </form>
            </div>
        </div>
        <script src="<?php echo getUrl("/js/create_story.js") ?>" defer></script>
</body>

</html>
