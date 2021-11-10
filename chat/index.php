<?php

require_once __DIR__ . "/../classes/init.php";
require_once __DIR__ . "/Message.php";
require_once __DIR__ . "/../classes/friend_system.php";

$friend_obj = new Friends($db_connection, me()->username);
$recent_messages = Message::getUserRecentMessages($me->username);

// var_dump($recent_messages);
$active_friends = $friend_obj->active_friends();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo getUrl("/css/main.css"); ?>">
    <link rel="stylesheet" href="<?php echo getUrl("/css/chat.css"); ?>">
    <link rel="stylesheet" href="<?php echo getUrl("/css/chat-recent.css"); ?>">
    <title>Chat - Recent</title>
</head>

<body>
    <div class="container">
        <?php require_once __DIR__ . "/../menu/menu.php"; ?>
        <div class="chatContainer">
            <header class="chatHeader">
                <h1 class="title">Recent chats</h1>
                <a href="<?php echo getUrl("/chat/chat_friends.php") ?>" class="btn">Friends</a>
            </header>
            <div class="activeFriendsContainer">
                <div class="activeFriendsList">
                    <?php foreach ($active_friends as $friend) : ?>
                        <div class="activeFriend">
                            <div class="activeFriend chatUserImg">
                                <img src="<?php echo getUrl("/images/{$friend->profile_pic}") ?>" alt="p">
                            </div>
                            <div class="activeFriendUserName">
                                <?php echo $friend->username; ?>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
            <div class="recentChatContainer">
                <?php if (empty($recent_messages)) : ?>
                    <p style="text-align: center;">You have no recent chat</p>
                <?php endif; ?>
                <div class="recentChatList">
                    <?php foreach ($recent_messages as $msg) :  ?>
                        <div data-unread-count="<?php echo $msg->unread_count; ?>" class="recentChat <?php echo boolval($msg->unread_count) ? "unread" : ""; ?>">
                            <div class="chatUserImg">
                                <img src="<?php echo getUrl("/images/{$msg->profile_pic}") ?>" alt="profile">
                            </div>
                            <div class="recentChatBody">
                                <a href="#" class="recentChatUserName"><?php echo "{$msg->from} - {$msg->to}"; ?></a>
                                <div class="recentChatMessage">
                                    <?php echo substr($msg->body, 0, 40) . (strlen($msg->body) > 40 ? "..." : ""); ?>
                                </div>
                                <div class="recentChatTime"><?php echo $date_obj->dateDiffStr($msg->created_at); ?></div>
                            </div>
                            <a class="recentChatLink" href="<?php echo getUrl("/chat/chat_room.php?user=" . ($msg->reciever === $me->username ? $msg->sender : $msg->reciever)) ?>"></a>
                        </div>
                    <?php endforeach ?>
                    <template>

                        <div data-unread-count="2" class="recentChat unread">
                            <div class="chatUserImg">
                                <img src="../images/default.png" alt="profile">
                            </div>
                            <div class="recentChatBody">
                                <a href="#" class="recentChatUserName">John Doe</a>
                                <div class="recentChatMessage">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                </div>
                                <div class="recentChatTime">2 hrs</div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

    </div>
    <script src="<?php echo getUrl("/js/recent.js"); ?>" defer></script>
</body>

</html>