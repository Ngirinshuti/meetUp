<?php
require_once __DIR__ . '/../classes/init.php';
require_once __DIR__ . '/../classes/friend_system.php';
require_once __DIR__ . '/../forms/Validator.php';
require_once __DIR__ . '/Group.php';


$friends = (new Friends($db_connection, $me->username))->getFriendsSorted();

$validator = new Validator();

$group = isset($_GET['group_add']) ? Group::findOne(intval($_GET['group_add'])) : null;

$validator->methodPost(function (Validator &$validator) {
    $rules = isset($_GET['create_group']) ? [
        'name' => ['not_empty' => true],
    ] : [];

    $validator->addData($_POST)->addRules($rules)->validate();

    if (!isset($_POST['members']) || !count($_POST['members'])) {
        $validator->setMainError("No members selected!")->saveMainError()
            ->redirect(current_url_full());
    } else {
        $validator->isValid(function (Validator $validator) {
            $members = $_POST['members'];

            $group = null;
            if (isset($_GET['group_add'])) {
                $group = $GLOBALS['group'];

                if (!$group->isAdmin(me()->username)) {
                    $validator->setMainError("Only admin can add members!")->saveMainError()
                        ->redirect(current_url_full());
                }

                $msg = count($members) . " new members joined '{$group->name}'.";
            } elseif (isset($_GET['create_group'])) {
                $name = $validator->valid_data['name'];
                $group = Group::create($name);

                $group->join(Auth::currentUser()->username, "admin");
                $msg = "Goup {$group->name} was created with you with " . count($members) . " other(s).";
            }

            $members_count = 0;

            foreach ($members as $member) {
                if (isset($_GET['group_add']) && $group->isMember($member)) {
                    continue;
                }

                $members_count +=  1;
                $group->join($member);
            }

            if (isset($_GET['group_add'])) {
                $members_count = $members_count ?: "No";
                $msg = "$members_count member(s) joined <{$group->name}>";
            } elseif (isset($_GET['create_group'])) {
                $msg = "Goup {$group->name} was created with you with " . count($members) . " other(s).";
            }

            $validator->setSuccessMsg($msg)
                ->redirect(getUrl("/chat/chat_groups.php?group={$group->id}"));
        });
    }

    $validator->isInvalid(function (Validator $validator) {
        $validator->setMainError("Correct errors below")->redirect(current_url_full());
    });
});


list($errors, $data, $errorClass, $mainError, $msg, $csrf) = $validator->helpers();

function in_members(string $username)
{
    $validator = $GLOBALS['validator'];

    $group = $GLOBALS['group'];
    $members = (isset($_POST['members']) ? $_POST['members'] : $validator->data("members")) ?: [];

    $in_selected_members = in_array($username, $members, true);

    $in_group_members = $group ? $group->isMember($username) : false;

    return $in_selected_members || $in_group_members;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php echo getUrl("/css/chat.css") ?>">
    <link rel="stylesheet" href="<?php echo getUrl("/css/chat-friends.css") ?>">
    <title>Chat - Groups</title>
</head>

<body>
    <div class="container">
        <?php require_once __DIR__ . '/../menu/menu.php'; ?>
        <div class="chatContainer">
            <header class="chatHeader">
                <a href="<?php echo getUrl("/chat/index.php") ?>" class="btn btn-icon"><i class="fa fa-arrow-left"></i></a>
                <h4 class="title">Friends</h4>
                <a href="<?php echo getUrl("/chat/chat_groups.php"); ?>" class="btn">Groups</a>
            </header>
            <?php echo $msg() ?>
            <?php echo $mainError(); ?>
            <div class="chatFriendsContainer">
                <?php if (isset($_GET['create_group']) && !isset($_GET['group_add'])) : ?>
                    <div class="mainFormContainer groupFormContainer">
                        <form id="group-create-form" action="" class="mainForm groupForm" method="POST">
                            <?php echo $csrf(); ?>
                            <div class="formHeader">
                            </div>
                            <div class="formBody">
                                <div class="mainInput <?php echo $errorClass('name'); ?>">
                                    <label for="group_name">Group Name</label>
                                    <input value="<?php echo $data('name') ?>" placeholder="Enter group name.." type="text" name="name" id="group_name">
                                    <?php echo $errors('name') ?>
                                </div>
                                <button form="group-create-form" style="display: inline-block; margin-bottom: 2rem;" type="submit">Create</button>
                            </div>
                            <h4 class="formText">Select friends to start a group</h4>
                        </form>
                    </div>
                <?php elseif (isset($_GET['group_add'])) : ?>
                    <div class="mainFormContainer groupFormContainer">
                        <form id="group-create-form" action="" class="mainForm groupForm" method="POST">
                            <?php echo $csrf(); ?>
                            <div class="formHeader">
                            </div>
                            <button form="group-create-form" style="display: inline-block; margin-bottom: 2rem;" type="submit">ADD MEMBERS IN '<?php echo $group->name ?>' group</button>
                            <h4 class="formText">Select friends to add in '<?php echo $group->name ?>' group</h4>
                        </form>
                    </div>


                <?php else : ?>
                    <!-- <div class="chatFriendsSearch">
                        <label for="search">
                            <i class="fa fa-search"></i>
                        </label>
                        <input data-friends-search-input placeholder="Search friends" type="search" name="search" id="search">
                    </div> -->
                <?php endif ?>

                <?php if (!count($friends)) : ?>
                    <p class="centered" style="text-align: center;">You have no friends</p>
                <?php endif ?>
                <div class="chatFriendsList">
                    <?php foreach ($friends as $friend) : ?>
                        <div class="chatFriend <?php echo (isset($_GET['create_group']) || isset($_GET['group_add'])) ? "selecting" : "" ?> <?php echo $friend->status === 'online' ? 'active' : ''; ?>">
                            <div class="chatUserImg">
                                <img src="<?php echo getUrl("/images/{$friend->profile_pic}") ?>" alt="profile">
                            </div>
                            <a href="#" class="chatFriendUserName"><?php echo $friend->username ?></a>
                            <?php if ($friend->status === 'offline') : ?>
                                <div class="chatFriendLastSeen">
                                    last seen: <?php echo  $date_obj->dateDiffStr($friend->last_seen); ?>
                                </div>
                            <?php endif ?>
                            <?php if (isset($_GET['create_group']) || isset($_GET['group_add'])) : ?>
                                <div class="chatFriendSelect">
                                    <input <?php echo in_members($friend->username) ? "checked" : ""; ?> form="group-create-form" type="checkbox" name="members[]" value="<?php echo $friend->username ?>">
                                </div>
                            <?php endif; ?>
                            <a href="<?php echo getUrl("/chat/chat_room.php?user={$friend->username}") ?>" class="chatFriendLink"></a>
                        </div>

                    <?php endforeach ?>
                    <template>
                        <div class="chatFriend">
                            <div class="chatUserImg">
                                <img src="../images/default.png" alt="profile">
                            </div>
                            <a href="#" class="chatFriendUserName">Jane Doe</a>
                            <div class="chatFriendSelect">
                                <input type="checkbox" name="select_friend">
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

    </div>
    <script src="<?php echo getUrl("/js/chat_friends.js") ?>" defer></script>
</body>

</html>