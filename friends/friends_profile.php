<?php

require_once __DIR__ . "/../classes/init.php";
require_once __DIR__ . "/../classes/friend_system.php";
require_once __DIR__ . '/../forms/Validator.php';
$user = isset($_GET['user']) ? User::findOne($_GET['user']) : $me;

if (!$user) {
    header("Location: " . getUrl("/404.php?url=" . current_url()));
    exit("Error");
}
$friends_obj = new Friends($db_connection, $user->username);
$validator = new Validator();

$validator->methodPost(function (Validator $validator) use ($friends_obj) {
    try {

        $valid = $validator->addRules([
            'action' => [
                'required' => true,
                "not_empty" => true,
                "in" => ['cancel', 'send', 'unfriend', 'confirm', 'deny']
            ],
            'friend' => ['required' => true, "not_empty" => true],
        ])->addData($_POST)->validate();

        if ($valid) {
            handleFriendsRequest($friends_obj, $success);
            $validator->setSuccessMsg($success);
        }
    } catch (FormError $e) {
        $validator->setMainError($e->getMessage());
    }
});

list($errors, $data, $errorClass, $mainError, $mainMsg, $csrf) = $validator->helpers();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/friends.css">
    <title>Friends</title>
</head>

<body>

    <div class="container">

        <?php require_once __DIR__ . "/../menu/menu.php"; ?>

        <div class="friendsNav"><label style="color:green;margin-left: 6cm; font-size: 22px;">
     <?php echo "ALL FRIENDS OF '".strtoupper($user->username)."' </label> <br><br>"; 
     $friends=$friends_obj->get_all_friends(); ?>
        </div>
        <?php echo $mainMsg(); ?>
        <?php echo $mainError(); ?>
        <?php echo $errors('action'); ?>
        <?php echo $errors('friend'); ?>
        <div class="friendsContainer">
            <div class="friendsList">
                <?php if (empty($friends)) : ?>
                    <p class="notFound">nothing found here</p>
                <?php endif; ?>
                <?php foreach ($friends as $friend) : ?>
                    <div class="friend">
                        <div class="friendImg">
                            <img src="<?php echo getUrl("/images/{$friend->profile_pic}"); ?>">
                        </div>
                        <form method="POST">
                        <div class="friendsBtns">
                            <a href="#" class="friendName"><?php echo $friend->username; ?></a>
                                <input type="hidden" name="friend" value="<?php echo $friend->username; ?>">
                                <?php echo $csrf(); ?>
                               
                            </div>
                            
                        </form>
                        <div><label><p><?php echo $friend->address ?></p></label>
                                </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</body>

</html>
<?php
function handleFriendsRequest(Friends $friends_obj, &$msg = "")
{
    $action = $_POST['action'];
    $friend = $_POST['friend'];

    try {

        switch ($action) {
            case 'send':
                $friends_obj->send_friend_request($friend);
                $msg = "Friend request sent to '{$friend}'";
                break;
            case 'cancel':
                $friends_obj->delete_friend_request($friend);
                $msg = "Unsent friend request to '{$friend}'";
                break;
            case 'deny':
                $friends_obj->respond_to_request($friend, false);
                $msg = "Rejected friend request from '{$friend}'";
                break;
            case 'unfriend':
                $friends_obj->delete_friend($friend);
                $msg = "You are no longer friends with '{$friend}'";
                break;
            case 'confirm':
                $friends_obj->respond_to_request($friend, true);
                $msg = "You are now friends with '{$friend}'";
                break;
        }
    } catch (PDOException $e) {
        throw new FormError($e->getMessage());
    }
}