<?php
// home feed file

require_once __DIR__ .  '/../classes/init.php';
require_once __DIR__ . "/./Post.php";
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
                "video" => ['exclude_if_exists' => ['image'], 'is_file' => __DIR__ . "/videos"],
                "username" => []
            ]
        )->addData($_POST)->validate();

        if ($validator->valid) {
            try {
                $post = Post::create(...$validator->valid_data);

                if (!$post) {
                    return new FormError("Something, went wrong! try again later ):");
                }

                $validator->setSuccessMsg("Post crearted!")->redirect(current_url());
            } catch (FormError $e) {
                $validator->setMainError($e->getMessage());
            }
        }
    }
);

$posts = Post::getFriendsPosts($user->username);
$post_paginator = new Paginator();
$post_paginator->updateHasMore(count($posts));

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="../assets/images/viachat.png">
    <title>Home Page</title>
    <link rel="stylesheet" href="<?php echo getUrl("/css/profile.css") ?>">
    <link rel="stylesheet" href="<?php echo getUrl("/css/home.css");  ?>">
    <title>Via Chat</title>
    <!-- <script src="<?php echo getUrl("/js/moreOptions.js");  ?>" defer></script> -->
</head>

<body>
    <div class="container">
        <?php require '../menu/menu.php'; ?>
        <div class="postFormContainer">
            <?php echo $msg(); ?>
            <form action="" class="postForm formContainer" method="post" enctype="multipart/form-data">
                <?php echo $csrf(); ?>
                <input type="hidden" name="username" value="<?php echo $me->username; ?>">
                <div class="desc mainInput">
                    <label for="area">Any words <?php echo $me ? "'{$me?->username}'" : ""; ?> ?</label>
                    <textarea autofocus name="post" data-emojiable="true" data-emoji-input="unicode" 
                    class=" <?php echo $errorClass('post'); ?>" id="area" 
                    placeholder="Write to share with friends (:"><?php echo $data('post'); ?></textarea>
                    <?php echo $errors('post'); ?>
                </div>
                <div data-file-preview class="choosenFileContainer hide">
                    <img src="../images/2504713.JPG" alt="">
                </div>
                <?php echo $errors('video'); ?>
                <?php echo $errors('image'); ?>
                <div class="postFormFooter">
                    <div class="fileIcons">
                        <button title="Choose image" type="button" class="fileIcon" data-image-select-icon>
                            <i class="fa fa-image"></i>
                        </button>
                        <button title="Choose video" type="button" class="fileIcon" data-video-select-icon>
                            <i class="fa fa-play"></i>
                        </button>
                        <input name="image" type="file" data-image-input accept="image/*">
                        <input name="video" type="file" data-video-input accept="video/*">
                    </div>

                    <button data-submit-btn type="submit">Post Now</button>
                </div>
            </form>

        </div>

        <!-- print posts -->
        <?php require_once __DIR__ . "/./print_posts.php"; ?>
        <!-- end print posts -->

    </div>

    <script src="<?php echo getUrl("/js/home.js");  ?>" defer></script>
    <script src="<?php echo getUrl("/js/comments.js");  ?>" defer></script>
</body>

</html>