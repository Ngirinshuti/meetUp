<<<<<<< HEAD
<?php

require_once __DIR__ . "/../classes/init.php";

require_once __DIR__ . "/../post/Post.php";
require_once __DIR__ . "/../forms/Validator.php";
require_once __DIR__ . "/../lib/createEmiji.php";
$user = isset($_GET['user']) ? User::findOne($_GET['user']) : $me;

if (!$user) {
    header("Location: " . getUrl("/404.php?url=" . current_url()));
    exit("Error");
}

$validator = new Validator();
list($errors, $data, $errorClass, $mainError, $msg, $csrf) = $validator->helpers();

$validator->methodPost(function (Validator $validator) {
    $validator->addRules(['profile_pic' => ["is_file" => toDir("/images")]])->validate();
    $validator->isValid(function (Validator $validator) {
        Auth::currentUser()->setProperty("profile_pic", $validator->valid_data['profile_pic']);
    })->redirect(getUrl("/friends/profile.php"));

    $validator->isInvalid(function (Validator $validator) {
        exit("Failed");
    })->redirect(getUrl("/friends/profile.php"));
});

$posts = Post::getUserPosts($user->username);
$post_paginator = new Paginator();
$post_paginator->updateHasMore(count($posts));

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="../assets/images/viachat.png">
    <title><?php echo ucfirst($user->username); ?> - Profile</title>
    <link rel="stylesheet" href="<?php echo getUrl("/css/profile.css") ?>">
    <link rel="stylesheet" href="<?php echo getUrl("/css/home.css") ?>">
    <script src="<?php echo getUrl("/js/comments.js"); ?>" defer></script>
    <script src="<?php echo getUrl("/js/moreOptions.js");  ?>" defer></script>
</head>

<body>
    <div class="container">
        <?php require_once __DIR__ . "/../menu/menu.php"; ?>
        <div class="profileContainer">
            <div data-profile-edit-modal class="profileEditModal hide">
                <form action="" method="POST" class="mainFormContainer" enctype="multipart/form-data">
                
                    <button type="button" data-pick-photo>
                        <i class="fa fa-image"></i>
                        Choose profile photo
                    </button>
                    <?php echo $csrf(); ?>
                    <input accept="image/*" type="file" name="profile_pic" data-edit-file-input>
                    <div data-photo-placeholder class="profilePhotoPlaceholder">
                    </div>
                    <button type="button" data-edit-form-submit class="success">Change</button>
                </form>
                <button type="button" data-hide-edit-modal class="danger">Cancel</button>
            </div>
            <div class="profileBg">
                <div class="profileImg">
                    <img src="<?php echo getUrl("/images/{$user->profile_pic}"); ?>" alt="">
                     <?php if ($user->username==$me->username):?>
                    <div class="profileImgEdit">

                        <button data-profile-edit-icon><i class="fa fa-edit"></i></button>
                    </div><?php endif; ?>
                </div>
                <div class="profileUserName"><?php echo $user->username; ?></div>
                <?php echo $errors("profile_pic"); ?>
                <?php if (!empty($user->about)) : ?>
                    <p class="profileUserAbout"><?php echo $user->about; ?></p>
                <?php endif; ?>
            </div>
            <div class="profileBtns">
                <?php if ($me->username != $user->username) : ?>

                <a href="#" class="btn successBtn profileBtn">
                    <i class="fa fa-send"></i>
                    Message
               </a>
                <?php endif; ?>
                <?php if ($me->username === $user->username) : ?>
                    
                    <a href="editProfile.php" class="btn successBtn profileBtn">
                        <i class="fa fa-edit"></i>
                        Edit Profile
                    </a>
                <?php endif; ?>
                <?php if ($me->username != $user->username) : ?>
                <a href="<?php echo getUrl("/friends/friends_profile.php?user={$user->username}") ?>" class="btn successBtn profileBtn">
                    <i class="fa fa-group"></i>
                    Friends
                </a>
                <?php endif; ?>
                 <?php if ($me->username === $user->username) : ?>
                <a href="friends.php" class="btn successBtn profileBtn">
                    <i class="fa fa-group"></i>
                    Friends
                </a>
                <?php endif; ?>
            </div>

        </div>
        
  <?php echo $msg(); ?>
        <!-- user posts -->
        <?php require_once __DIR__ . "/../post/print_posts.php"; ?>
        <!-- end user posts -->
    </div>
    <!-- comment template -->
    <template data-comment-template>
        <div class="comment">
            <div class="commentUserImg">
                <img src="<?php echo getUrl("/images/{$user->profile_pic}");  ?>" alt="comment">
            </div>
            <div class="commentContent">
                <span class="commentUserName"><?php echo $user->username; ?></span>
                <div class="commentBody">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Id, eius.</div>
                <div class="commentBtns">
                    <button class="commentBtn likeBtn" data-comment-like>Like 1</button>
                </div>
            </div>
        </div>
    </template>
    <!-- comment template -->
    <script src="<?php echo getUrl("/js/profile.js") ?>" defer></script>
  
</body>

=======
<?php

require_once __DIR__ . "/../classes/init.php";

require_once __DIR__ . "/../post/Post.php";
require_once __DIR__ . "/../forms/Validator.php";
require_once __DIR__ . "/../lib/createEmiji.php";
$user = isset($_GET['user']) ? User::findOne($_GET['user']) : $me;

if (!$user) {
    header("Location: " . getUrl("/404.php?url=" . current_url()));
    exit("Error");
}

$validator = new Validator();
list($errors, $data, $errorClass, $mainError, $msg, $csrf) = $validator->helpers();

$validator->methodPost(function (Validator $validator) {
    $validator->addRules(['profile_pic' => ["is_file" => toDir("/images")]])->validate();
    $validator->isValid(function (Validator $validator) {
        Auth::currentUser()->setProperty("profile_pic", $validator->valid_data['profile_pic']);
    })->redirect(getUrl("/friends/profile.php"));

    $validator->isInvalid(function (Validator $validator) {
        exit("Failed");
    })->redirect(getUrl("/friends/profile.php"));
});

$posts = Post::getUserPosts($user->username);
$post_paginator = new Paginator();
$post_paginator->updateHasMore(count($posts));

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($user->username); ?> - Profile</title>
    <link rel="stylesheet" href="<?php echo getUrl("/css/profile.css") ?>">
    <link rel="stylesheet" href="<?php echo getUrl("/css/home.css") ?>">
    <script src="<?php echo getUrl("/js/comments.js"); ?>" defer></script>
</head>

<body>
    <div class="container">
        <?php require_once __DIR__ . "/../menu/menu.php"; ?>
        <div class="profileContainer">
            <?php if ($user->username === me()->username) : ?>
            <div data-profile-edit-modal class="profileEditModal hide">
                <form action="" method="POST" class="mainFormContainer" enctype="multipart/form-data">

                    <button type="button" data-pick-photo>
                        <i class="fa fa-image"></i>
                        Choose profile photo
                    </button>
                    <?php echo $csrf(); ?>
                    <input accept="image/*" type="file" name="profile_pic" data-edit-file-input>
                    <div data-photo-placeholder class="profilePhotoPlaceholder">
                    </div>
                    <button type="button" data-edit-form-submit class="success">Change</button>
                </form>
                <button type="button" data-hide-edit-modal class="danger">Cancel</button>
            </div>
            <?php endif; ?>
            <div class="profileBg">
                <div class="profileImg">
                    <img src="<?php echo getUrl("/images/{$user->profile_pic}"); ?>" alt="">
                    <?php if (me()->username === $user->username): ?>
                    <div class="profileImgEdit">

                        <button data-profile-edit-icon><i class="fa fa-edit"></i></button>
                    </div>
                    <?php endif ?>
                </div>
                <div class="profileUserName"><?php echo $user->username; ?></div>
                <?php echo $errors("profile_pic"); ?>
                <?php if (!empty($user->about)) : ?>
                    <p class="profileUserAbout"><?php echo $user->about; ?></p>
                <?php endif; ?>
            </div>
            <div class="profileBtns">
                <?php if ($me->username != $user->username) : ?>
                <a href="<?php echo getUrl("/chat/chat_room.php?user={$user->username}") ?>" class="btn successBtn profileBtn">
                    <i class="fa fa-send"></i>
                    Message
               </a>
                <?php endif; ?>
                <?php if ($me->username === $user->username) : ?>
                    <a href="editProfile.php" class="btn successBtn profileBtn">
                        <i class="fa fa-edit"></i>
                        Edit Profile
                    </a>
                <?php endif; ?>
                <?php if ($me->username != $user->username) : ?>
                <a href="<?php echo getUrl("/friends/friends_profile.php?user={$user->username}") ?>" class="btn successBtn profileBtn">
                    <i class="fa fa-group"></i>
                    Friends
                </a>
                <?php endif; ?>
                 <?php if ($me->username === $user->username) : ?>
                <a href="friends.php" class="btn successBtn profileBtn">
                    <i class="fa fa-group"></i>
                    Friends
                </a>
                <?php endif; ?>
            </div>

        </div>

        <!-- user posts -->
        <?php require_once __DIR__ . "/../post/print_posts.php"; ?>
        <!-- end user posts -->
    </div>
    <!-- comment template -->
    <template data-comment-template>
        <div class="comment">
            <div class="commentUserImg">
                <img src="<?php echo getUrl("/images/");  ?>" alt="comment">
            </div>
            <div class="commentContent">
                <span class="commentUserName"><?php echo $user->username; ?></span>
                <div class="commentBody">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Id, eius.</div>
                <div class="commentBtns">
                    <button class="commentBtn likeBtn" data-comment-like>Like 1</button>
                </div>
            </div>
        </div>
    </template>
    <!-- comment template -->
    <script src="<?php echo getUrl("/js/profile.js") ?>" defer></script>
</body>

>>>>>>> combine
</html>