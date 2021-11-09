<?php
// friends script

require_once __DIR__ . "/../classes/init.php";
require_once __DIR__ . "/../classes/friend_system.php";
require_once __DIR__ . '/../forms/Validator.php';

$friends_obj = new Friends($db_connection, $me->username);
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

$view_types = ["recieved", "sent", "friends", "discover"];

$friends = [];

$view_type = isset($_GET['view']) ? $_GET['view'] : $view_types[0];

switch ($view_type) {
    case 'recieved':
        $friends = $friends_obj->get_friend_requests();
        break;

    case 'sent':
        $friends = $friends_obj->get_sent_requests();
        break;

    case 'friends':
        $friends = $friends_obj->get_all_friends();
        break;

    case 'discover':
        $friends = $me->getNonFriends();
        break;
}




function activeTab($tab)
{
    echo $GLOBALS['view_type'] === $tab ? " active " : "";
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/friends.css">
    <link rel="icon" type="image" href="../assets/images/viachat.png">
    <title>Friends</title>
</head>

<body>

    <div class="container">

        <?php require_once __DIR__ . "/../menu/menu.php"; ?>

        <div class="friendsNav">
            <a href="<?php echo current_url() . "?view=recieved"; ?>" class="friendsNavBtn <?php activeTab('recieved'); ?>">Friend Requests</a>
            <a href="<?php echo current_url() . "?view=sent"; ?>" class="friendsNavBtn <?php activeTab('sent'); ?>">Sent Requests</a>
            <a href="<?php echo current_url() . "?view=friends"; ?>" class="friendsNavBtn <?php activeTab('friends'); ?>">Friends</a>
            <a href="<?php echo current_url() . "?view=discover"; ?>" class="friendsNavBtn <?php activeTab('discover'); ?>">Discover People</a>
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
                            <img src="<?php echo getUrl("/images/{$friend->profile_pic}"); ?>" alt="friend">
                        </div>
                        <form method="POST">
                        <div class="friendsBtns">
                            <a href="<?php echo getUrl("/friends/profile.php?user={$friend->username}") ?>" class="friendName"><?php echo $friend->username; ?></a>
                                <input type="hidden" name="friend" value="<?php echo $friend->username; ?>">
                                <?php echo $csrf(); ?>
                                <?php switch ($view_type):
                                    case "recieved":
                                ?>
                                        <button name="action" value="confirm" class="friendsBtn success" type="submit">Accept</button>
                                        <button name="action" value="deny" class="friendsBtn danger" type="submit">Deny</button>
                                    <?php
                                        break;
                                    case "sent": ?>
                                        <button name="action" value="cancel" class="friendsBtn danger" type="submit">Cancel</button>
                                    <?php
                                        break;
                                    case "friends": ?>
                                        <button name="action" value="unfriend" class="friendsBtn danger" type="submit">Unfriend</button>
                                    <?php
                                        break;
                                    case "discover": ?>
                                        <button name="action" value="send" class="friendsBtn" type="submit">Add friend</button>
                                <?php endswitch; ?>
                            </div>
                        </form>
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
