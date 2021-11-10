<?php
require_once __DIR__ .  '/../classes/init.php';
require_once __DIR__ . "/./Post.php";
require_once __DIR__ . "/../classes/friend_system.php";
require_once __DIR__ . "/../forms/Validator.php";
require_once __DIR__ . "/../lib/createEmiji.php";
$id = $_POST['post_id'];
$Post = new Post($id);
$post = $Post::findOne($id);
$friends_obj = new Friends($db_connection, $me->username);
$friends = $friends_obj->get_all_friends();
if (isset($_POST['shareNow'])) {


    $post->share($_POST['comment'], $me->username, $post->image, $post->video, $post->id);
    Session::set("forms.success.msg", "Post shared");
    header("location:../friends/profile.php");
    exit;
}


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="../assets/images/viachat.png">
    <title>Share Post</title>
    <link rel="stylesheet" href="<?php echo getUrl("/css/home.css");  ?>">
    <title>Via Chat</title>
</head>

<body>
    <div class="container">
        <?php require '../menu/menu.php'; ?>

        <form method="POST" action="" class="postShareForm mainForm">
            <div class="formHeader">
                <a href="#!" class="formBrand">ViaChat</a>
                <h2>Share Post</h2>
            </div>
            <div class="postShareEditor">
                <div class="formBody">

                    <div class="inputContainer">
                        <label for="sharepost">Say something</label>
                        <textarea id="sharepost" placeholder="What do you say..." name="comment" data-emojiable="true" data-emoji-input="unicode" class="storyCreateArea"></textarea>
                    </div>
                    <input type="hidden" name="post_id" value="<?php echo $post->id ?>">
                    <div class="userpost" id="post<?php echo $post->id; ?>">

                        <div class="postHeader">
                            <a href="<?php echo getUrl("/friends/profile.php?user={$post->username}") ?>" class="postUser">
                                <div class="userProfile">
                                    <img src="../images/<?php echo $post->owner()->profile_pic; ?>" />
                                </div>
                                <div class="userName"><?php echo $post->username; ?></div>
                            </a>
                            <div class="postTime">
                                <?php echo $date_obj->dateDiffStr($post->date); ?>
                            </div>
                        </div>
                        <div class="postBody">
                            <?php if ($post->post) : ?>
                                <div class="postText">
                                    <?php echo $post->post; ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($post->image && !$post->video) : ?>
                                <img class="postImg" src="<?php echo getUrl("/post/images/{$post->image}"); ?>" />
                            <?php endif; ?>
                            <?php if ($post->video) : ?>
                                <video class="postVideo" src="<?php echo getUrl("/post/videos/{$post->video}"); ?>" controls>
                                <?php endif; ?>
                        </div>
                    </div>
                    <button name="shareNow" type="submit">Share now</button>
                </div>
            </div>
        </form>

    </div>



</body>

</html>