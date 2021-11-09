<?php 
require_once __DIR__ .  '/../classes/init.php';
require_once __DIR__ . "/./Post.php";
require_once __DIR__ . "/../classes/friend_system.php";
require_once __DIR__ . "/../forms/Validator.php";
require_once __DIR__ . "/../lib/createEmiji.php";
$id=$_POST['post_id'];
$Post=new Post($id);
$posts = $Post::findOne($id);
$friends_obj = new Friends($db_connection, $me->username);
$friends = $friends_obj->get_all_friends();
if (isset($_POST['shareNow'])) {


     $posts->share($_POST['comment'],$me->username,$posts->image,$posts->video,$posts->id);
     header("location:../friends/profile.php?message=msg");
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
 <form method="POST" action="">
                
        <div class="userpost" id="post<?php echo $posts->id; ?>">

<fieldset style="height:2cm;max-width: 100%;border: 0px; ">
                    <div class="inputContainer">
                  Add comment: <textarea name="comment" data-emojiable="true"
                        data-emoji-input="unicode" id="enterText"class="storyCreateArea"
                        id="desc" placeholder="Add text..." style="border:20px"></textarea></div>
                </fieldset>
<input type="hidden" name="post_id" value="<?php $posts->id ?>">
            <div class="postHeader" style="margin-top:1cm">
                <a href="<?php echo getUrl("/friends/profile.php?user={$posts->username}") ?>" class="postUser">
                    <div class="userProfile">
                        <img src="../images/<?php echo $posts->owner()->profile_pic; ?>" />
                    </div>
                    <div class="userName"><?php echo $posts->username; ?></div>
                </a>
                <div class="postTime">
                    <?php echo $date_obj->dateDiffStr($posts->date); ?>
                </div>
            </div>
            <div class="postBody">
                <?php if ($posts->post) : ?>
                    <div class="postText">
                        <?php echo $posts->post; ?>
                    </div>
                <?php endif; ?>
                <?php if ($posts->image && !$posts->video) : ?>
                    <img class="postImg" src="<?php echo getUrl("/post/images/{$posts->image}"); ?>" />
                <?php endif; ?>
                <?php if ($posts->video) : ?>
                    <video class="postVideo" src="<?php echo getUrl("/post/videos/{$posts->video}"); ?>" controls>
                    <?php endif; ?>
            </div>
            <div>
            	<!--Tag friends<br>


  <div class="friendsList">
                <?php// if (empty($friends)) : ?>
                    <p class="notFound">You don't have any friend yet!</p>
                <?php// endif; ?>

                <?php// foreach ($friends as $friend) : ?>
                    <div class="friend">
                       
                       
                        <div class="friendsBtns">
                            <a href="#" class="friendName">
                                <input type="checkbox" name="user[]" value="<?php// echo $friend->username; ?>"> <?php //echo $friend->fname." ".$friend->lname." (".$friend->username.")"; ?></a>
                                <input type="hidden" name="friend" value="<?php //echo $friend->username; ?>">
                                
                               
                             
                            </div>
                       
                    </div>
                <?php //endforeach; ?><br>-->
                <button name="shareNow" type="submit" style="margin-left:5cm;">Share now</button>
          
            </div>  </form>

            	

           </body></html>