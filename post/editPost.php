<?php
// home feed file

require_once __DIR__ . "/../classes/init.php";

require_once __DIR__ . "/../post/Post.php";
require_once __DIR__ . "/../forms/Validator.php";
require_once __DIR__ . "/../lib/createEmiji.php";
$user = $me;

if (!isset($_GET['id']) || !($post = Post::findOne($_GET['id']))) {
    header("Location: " . getUrl("/post/home.php"));
    exit();
}

$validator = new Validator([
    "post" => ["required_without" => ["image", "video"]],
    "image" => ['is_file' => __DIR__ . "/images"],
    "video" => ['exclude_if_exists' => ['image'], 'is_file' => __DIR__ . "/videos"],
]);

$validator->addData(get_object_vars($post));


$validator->methodPost(
    function (Validator $validator) {
        $validator->addData($_POST)->validate();

        if ($validator->valid) {
            try {
                $post =  $GLOBALS['post'];

                // delete existing post files
                if (!empty($validator->valid_data['video']) && is_file(toDir("/post/videos/{$post->video}"))) {
                    unlink(toDir("/post/videos/{$post->video}"));
                }

                if (!empty($validator->valid_data['image']) && is_file(toDir("/post/images/{$post->image}"))) {
                    unlink(toDir("/post/images/{$post->image}"));
                }


                $post = $post->update(...$validator->valid_data);

                if (!$post) {
                    return new FormError("Something, went wrong! try again later ):");
                }


                $validator->setSuccessMsg("Post updated!")->redirect(current_url_full());
            } catch (FormError $e) {
                $validator->setMainError($e->getMessage())->redirect(current_url_full());
            }
        }
    }
);

list($errors, $data, $errorClass, $mainError, $msg, $csrf) = $validator->helpers();

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
    <script defer src="<?php echo getUrl("/js/home.js");  ?>"></script>
    <title>Via Chat</title>
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
                    <textarea value="" autofocus name="post" data-emojiable="true" data-emoji-input="unicode" class=" <?php echo $errorClass('post'); ?>" id="area" placeholder="Write to share with friends (:"><?php echo $data('post'); ?></textarea>
                    <?php echo $errors('post'); ?>
                </div>
                <div data-file-preview class="choosenFileContainer">
                    <?php if ($post->video) : ?>
                        <video src='<?php echo getUrl("/post/videos/{$post->video}") ?>' controls>
                    <?php endif ?>
                    <?php if ($post->image) : ?>
                        <img src="<?php echo getUrl("/post/images/{$post->image}") ?>" alt="post image">
                    <?php endif ?>
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

                    <button data-submit-btn type="submit">Edit Now</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>