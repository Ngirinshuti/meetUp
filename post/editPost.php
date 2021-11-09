<?php
// home feed file

require_once __DIR__ . "/../classes/init.php";

require_once __DIR__ . "/../post/Post.php";
require_once __DIR__ . "/../forms/Validator.php";
require_once __DIR__ . "/../lib/createEmiji.php";
$user = $me;


$validator = new Validator();
list($errors, $data, $errorClass, $mainError, $msg, $csrf) = $validator->helpers();


$validator->methodPost(
    function (Validator $validator) {
        $validator->addRules(
            [
                "post" => ["required_without" => ["image", "video"]],
                "image" => ['is_file' => __DIR__ . "/images"],
                "video" => ['is_file' => __DIR__ . "/videos"],
                 "p_id" => []
            ]
        )->addData($_POST)->validate();

        if ($validator->valid) {
            try {
                $post = Post::update(...$validator->valid_data);

                if (!$post) {
                    return new FormError("Something, went wrong! try again later ):");
                }

                header("Location: ./home.php?msg=Post updated!");
                exit();
            } catch (FormError $e) {
                $validator->setMainError($e->getMessage());
            }
        }
    }
);
$pos=new Post;
$posts = $pos->findOne($_GET['id']);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="../assets/images/viachat.png">
    <title>Edit Post</title>
    <link rel="stylesheet" href="<?php echo getUrl("/css/home.css");  ?>">
    <title>Via Chat</title>
</head>

<body>
    <div class="container">
        <?php require '../menu/menu.php'; ?>
        <div class="postFormContainer">
            <form action="" class="postForm formContainer" method="post" enctype="multipart/form-data">
                <?php echo $csrf(); ?>
                <input type="hidden" name="p_id" value="<?php echo $posts->id; ?>">
                <div class="desc mainInput" style="margin-left:5cm;width: 10cm">
                    <label for="area">Edit your post </label>

                    <textarea autofocus name="post" data-emojiable="true" data-emoji-input="unicode" class=" <?php echo $errorClass('post'); ?>" id="area" style="height: 3cm;" ><?php echo $posts->post; ?></textarea>
                    <div style="margin-top: -0.8cm;width: 10cm;" class="iconContainer"><i title="Add Image" class="fa fa-image fa-2x" id="icon" data-story-edit-icon_photo></i>&nbsp;&nbsp;&nbsp;<i title="Add Video" class="fa fa-file-video-o fa-2x" aria-hidden="true" id="clip"></i></div>

                    <?php echo $errors('post'); ?>
                </div>
                <div class="filesWrapper">
                    <input data-tooltip="Post an image" class=" <?php echo $errorClass('image'); ?>" data-image-input accept="image/*" id="image" name="image" type="file" title="Picture"  style="display: none;" onchange="loadFile(event)"/>
                    <?php echo $errors('image'); ?>
                    <input class=" <?php echo $errorClass('video'); ?>" data-video-input accept="video/*" id="video" type="file" name="video" title="Video"  style="display: none;"
                    onchange="loadFile(event)">
                    <?php echo $errors('video'); ?>
                </div><br>
                 <div> <button type="submit" style="display: inline-block;max-width:3cm;height: 1.2cm;margin-left: -7cm;margin-top: 7cm;">Post Now</button>
     <div class="display" style="float: right;width: 180px;height: 50px;margin-top: -1.2cm;">
    <p><img id="output"  display-picture style="float: right;" title="Image you are going to post" /></p>
    <p><video id="outputv" autoplay="true" width="250" title="Video you are going to post" ></video></p>
                    </div>

                 </div>
            </form>

        </div>

             
    </div>

    <script src="<?php echo getUrl("/js/comments.js");  ?>" defer></script>
    <script defer>
        window.addEventListener("DOMContentLoaded", e => {
            const imageInput = document.querySelector("[data-image-input]")
            const videoInput = document.querySelector("[data-video-input]")

            if (!videoInput || !imageInput) {
                return alert("Something is wrong!");
            }

            videoInput.addEventListener("change", e => {
                const vidElement = document.createElement("video")
                if (videoInput.files[0] && !vidElement.canPlayType(videoInput.files[0].type)) {
                    alert("Video file type not supported!")
                    // videoInput.setAttribute('value', '');
                    return videoInput.form.reset();
                }
                if (videoInput.files[0] && videoInput.files[0].size >= 41943040) {
                    alert("Choosen file is bigger than " + Math.round(41943040 / 1000000) + "MB");
                    return videoInput.form.reset();
                }
            })

            imageInput.addEventListener("change", e => {
                if (imageInput.files[0] && !(imageInput.files[0].type.startsWith("image/"))) {
                    alert("Image file type not supported!")
                    // videoInput.setAttribute('value', '');
                    return videoInput.form.reset();
                }
            })
        })
    </script>
<script>
 var loadFile = function(event) {
    var image = document.getElementById('output');
    image.src = URL.createObjectURL(event.target.files[0]);
      var video = document.getElementById('outputv');
    video.src = URL.createObjectURL(event.target.files[0]);
};
const editButtonPhoto = document.querySelector("[data-story-edit-icon_photo]");
const editModalPhoto= document.querySelector("[display-picture]");
editButtonPhoto.addEventListener("click", (e) => {
    editModalPhoto.classList.remove("hide");
});



</script>
   <script>
    let clip=document.getElementById("clip");
let video=document.getElementById("video");
let icon=document.getElementById("icon");
let file=document.getElementById("image");
icon.addEventListener("click",function(){
    file.click();
})
clip.addEventListener("click",function(){
    video.click();
}) </script>
</body>

</html>