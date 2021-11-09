<?php

require_once __DIR__ . '/../classes/init.php';
require_once __DIR__ . '/../classes/friend_system.php';
require_once __DIR__ . '/Message.php';
require_once __DIR__ . '/Group.php';
require_once __DIR__ . '/../lib/createEmiji.php';


$user = isset($_GET['user']) ? User::findOne($_GET['user']) : null;
$group = isset($_GET['group']) ? Group::findOne($_GET['group']) : null;

if ($group && !$group->isMember(me()->username)) {
    Session::set("forms.errors.msg", "You are not a member of '{$group->name}'");
    header("Location: " . getUrl("/chat/chat_groups.php?group=".$group->id));
    exit;
}

if ($user) {
    if ($user?->username === $me->username) {
        exit("Something! Went wrong.");
    }
    $messages = Message::getConversation($me->username, $user->username);
} elseif ($group) {
    $messages = $group->getMessages();
} else {
    header("Location: " . getUrl('/404.php?msg=Something is wrong'));
    exit;
}

function shouldCombine(int $i)
{
    $messages = $GLOBALS['messages'];
    $date_obj = $GLOBALS['date_obj'];

    $prev = isset($messages[$i - 1]) ? $messages[$i - 1] : null;
    $next = isset($messages[$i + 1]) ? $messages[$i + 1] : null;
    $current = $messages[$i];


    if ($next === null) {
        return false;
    }
    if ($current->sender === $next->sender && $date_obj->underAMinute($next->created_at, $current->created_at)) {
        return true;
    }
}

$active_count = (new Friends($db_connection, $me->username))->activeFriendsCount();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/font-awesome-4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/chat.css">
    <link rel="stylesheet" href="../css/chat-room.css">
    <link rel="icon" type="image" href="../assets/images/viachat.png">
    <title>Chat - <?php echo $user?->username . $group?->name; ?></title>
</head>

<body>
    <div class="container">
        <?php require_once __DIR__ . '/../menu/menu.php'; ?>
        <div class="chatContainer">
            <header class="chatHeader">
                <a href="<?php echo getUrl("/chat/index.php") ?>" class="btn btn-icon"><i class="fa fa fa-arrow-circle-left"></i></a>
                <h5 class="title"><?php echo $user?->username . $group?->name; ?></h5>
                <div class="headerBtns">
                    <a href="<?php echo getUrl("/chat/chat_friends.php"); ?>" class="btn">Active (<?php echo $active_count; ?>)</a>
                </div>
            </header>
            <div class="chatRoomContainer">
                <?php if (empty($messages)) : ?>
                    <p data-no-messages class="centered" style="text-align: center;">You have no chat yet.</p>
                <?php endif; ?>
                <div class="chatMessageList">
                    <?php foreach ($messages as $i => $msg) : ?>
                        <?php if ($i === 0 || !shouldCombine($i - 1)) : ?>
                            <div class="chatMessageGroup <?php echo $msg->sender === $me->username ? "sent" : "recieved"; ?>">
                                <div class="chatMessageUser">
                                    <div class="chatUserImg">
                                        <img src="<?php echo getUrl("/images/{$msg->profile_pic}") ?>" alt="profile">
                                    </div>
                                </div>
                            <?php endif ?>
                            <div class="chatMessage">
                                <div class="chatMessageBody"><?php echo $msg->body; ?></div>
                            </div>
                            <?php if (!shouldCombine($i)) : ?>
                                <div class="chatMessageInfo">
                                    <div class="chatMessageTime"><?php echo $date_obj->dateDiffStr($msg->created_at); ?></div>
                                    <div class="chatStatus seen">
                                        <?php echo $msg->status; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="chatRoomInput">
                    <textarea data-emojiable="true" data-emoji-input="unicode" data-chat-friend="<?php echo $user?->username; ?>" data-me="<?php echo $me->username; ?>" data-last-message-sender="<?php echo (end($messages) ?: null)?->sender; ?>" data-last-message-date="<?php echo (end($messages) ?: null)?->created_at ?>" data-csrf-key="<?php echo $my_csrf->get_token_id() ?>" data-csrf-token="<?php echo $my_csrf->get_token() ?>" data-group-id="<?php echo $group?->id; ?>" data-reciever="<?php echo $user?->username; ?>" data-chat-input placeholder="Message.." autofocus></textarea>
                    <button data-chat-send class="btn-icon"><i class="fa fa-send"></i></button>
                </div>
            </div>
        </div>
    </div>
    </div>

    <template data-message-template>
        <!-- <div class="chatMessageGroup"> -->
        <div class="chatMessageUser">
            <div class="chatUserImg">
                <img src="<?php echo getUrl("/images/") ?>" alt="profile">
            </div>
        </div>
        <div class="chatMessage">
            <div class="chatMessageBody"></div>
        </div>
        <div class="chatMessageInfo">
            <div class="chatMessageTime"></div>
            <div class="chatStatus"></div>
        </div>
        <!-- </div> -->
    </template>
    <script src="<?php echo getUrl("/js/chat_room.js") ?>" defer></script>
</body>

</html>