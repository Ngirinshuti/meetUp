<?php

require_once __DIR__ . '/../classes/init.php';
require_once __DIR__ . '/Group.php';
require_once __DIR__ . '/../forms/Validator.php';

$group = isset($_GET['group']) ? Group::findOne($_GET['group']) : null;

$validator = new Validator();

list($errors, $data, $errorClass, $mainError, $msg, $csrf) = $validator->helpers();

$groups = !$group ? Group::all() : [];


$validator->methodPost(function (Validator $validator) {
    $validator->addRules([
        'action' => [
            "not_empty" => true,
            "in" => ['leave', 'join', 'delete']
        ],
        'member' => ["not_empty" => true],
    ])->addData($_POST)->validate();

    $validator->isValid(function (Validator $validator) {
        try {
            $group = $GLOBALS['group'] ?: Group::findOne($_POST['group_id']);
            handleGroupAction($group, $success_msg);
            $validator->setSuccessMsg($success_msg)->redirect(current_url_full());
        } catch (FormError $e) {
            $validator->setMainError($e->getMessage())->redirect(current_url_full());
        }
    });
});


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo getUrl("/css/chat_groups.css"); ?>">
    <link rel="stylesheet" href="<?php echo getUrl("/css/chat.css"); ?>">
    <title>Groups</title>
</head>

<body>
    <div class="container">
        <?php require_once __DIR__ . '/../menu/menu.php'; ?>

        <div class="chatContainer">
            <header class="chatHeader">
                <a href="<?php echo getBackUrl(); ?>" class="btn btn-icon"><i class="fa fa-arrow-left"></i></a>
                <h4 class="title"><?php echo $group ? $group->name : "Groups" ?></h4>
                <?php if ($group) : ?>
                    <a class="btn" href="<?php echo getUrl("/chat/chat_groups.php") ?>">
                        ALL Groups
                    </a>
                <?php else : ?>
                    <a class="btn" href="<?php echo getUrl("/chat/chat_friends.php?create_group") ?>" data-group-create>
                        <i class="fa fa-group"></i>
                        Create Group
                    </a>
                <?php endif; ?>
            </header>

            <?php echo $msg() ?>
            <?php echo $mainError(); ?>


            <?php if (!$group) : ?>
                <div class="chatGroupsContainer">
                    <?php if (!count($groups)) : ?>
                        <p class="centered" style="text-align: center;">You don't belong to any group yet.</p>
                    <?php endif ?>
                    <h3 class="">Groups</h3>
                    <div class="chatGroupList">
                        <?php foreach ($groups as $group) : ?>
                            <div class="chatGroup">
                                <a href="<?php echo getUrl("/chat/chat_groups.php?group={$group->id}") ?>" class="chatGroupUserName"><?php echo $group->name ?></a>
                                <div class="chatGroupBtns">
                                    <form id="group-action-form" action="" method="post">
                                        <input type="hidden" name="group_id" value="<?php echo $group->id; ?>">
                                        <input type="hidden" name="member" value="<?php echo me()->username; ?>">
                                        <?php echo $csrf() ?>
                                        <div class="groupProfileBtns">
                                            <?php if ($group->isAdmin(me()->username)) : ?>
                                                <button name="action" value="delete" class="danger"><i class="fa fa-trash"></i> DELETE</button>
                                                <a href="<?php echo getUrl("/chat/chat_room.php?group=" . $group->id) ?>" class="btn groupChatBtn"><i class="fa fa-wechat"></i> Chat</a>
                                            <?php elseif ($group->isMember(me()->username)) : ?>
                                                <button name="action" value="leave" type="submit" class="danger">Leave</button>
                                                <a href="<?php echo getUrl("/chat/chat_room.php?group=" . $group->id) ?>" class="btn groupChatBtn"><i class="fa fa-wechat"></i> Chat</a>
                                            <?php else : ?>
                                                <button name="action" value="join" type="submit" class="success">JOIN</button>
                                            <?php endif; ?>
                                        </div>
                                    </form>


                                    <!-- <form action="" method="post">
                                        <input type="hidden" name="member" value="<?php echo me()->username; ?>">
                                        <button name="action" value="leave" class="btn danger"> <?php echo $group->isAdmin(me()->username) ? "Delete" : "Leave"; ?></button>
                                    </form> -->
                                    <!-- <a href="<?php echo getUrl("/chat/chat_room.php?group={$group->id}") ?>" class="btn">Messages</a> -->
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>

            <?php else : ?>
                <div class="groupProfile">
                    <div class="groupImg">
                        <?php echo $group->name[0]; ?>
                    </div>
                    <div class="groupName"><?php echo $group->name ?></div>
                    <form id="group-action-form" action="" method="post">
                        <input type="hidden" name="member" value="<?php echo me()->username; ?>">
                        <?php echo $csrf() ?>
                        <div class="groupProfileBtns">
                            <?php if ($group->isAdmin(me()->username)) : ?>
                                <a href="<?php echo getUrl("/chat/chat_friends.php?group_add=" . $group->id) ?>" class="btn success"><i class="fa fa-add"></i> Add member(s)</a>
                                <button name="action" value="delete" class="danger"><i class="fa fa-trash"></i> DELETE</button>
                                <a href="<?php echo getUrl("/chat/chat_room.php?group=" . $group->id) ?>" class="btn groupChatBtn"><i class="fa fa-wechat"></i> Chat</a>
                            <?php elseif ($group->isMember(me()->username)) : ?>
                                <button name="action" value="leave" type="submit" class="danger">Leave</button>
                                <a href="<?php echo getUrl("/chat/chat_room.php?group=" . $group->id) ?>" class="btn groupChatBtn"><i class="fa fa-wechat"></i> Chat</a>
                            <?php else : ?>
                                <button name="action" value="join" type="submit" class="success">JOIN</button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
                <h5 class="groupTitle">Members</h5>
                <div class="groupMembers">
                    <?php foreach ($group->getMembers() as $member) : ?>
                        <div class="groupMember">
                            <div class="groupMemberImg chatUserImg">
                                <img src="<?php echo getUrl("/images/{$member->profile_pic}") ?>" alt="p">
                            </div>
                            <div class="groupMemberUserName">
                                <?php echo $member->username; ?>
                            </div>
                            <div class="groupMemberRole">
                                <?php echo $member->role; ?>
                            </div>
                            <div class="groupMemberBtn">
                                <?php if ($group->isAdmin(me()->username)) : ?>
                                    <form action="" method="POST">
                                        <?php echo $csrf() ?>
                                        <input type="hidden" name="member" value="<?php echo $member->username; ?>">
                                        <button name="action" value="leave" class="danger"><i class="fa fa-trash"></i> Remove</button>
                                    </form>
                                <?php endif; ?>

                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            <?php endif ?>
        </div>
    </div>

</body>

</html>



<?php



function handleGroupAction(Group &$group, &$msg = "")
{
    $action = $_POST['action'];
    $member = $_POST['member'];

    if (!($member === me()->username) && !$group->isAdmin(me()->username)) {
        throw new FormError("Access denied!");
    }

    try {
        switch ($action) {
            case 'leave':
                $group->leave($member);
                if (me()->username === $member) {
                    $action_type = $group->isAdmin(me()->username) ? "deleted" : "left";
                    $msg = "You {$action_type} '{$group->name}' group!";
                } else {
                    $msg = "$member is no longer a member of '{$group->name}'";
                }
                break;
            case 'join':
                if ($group->isMember($member)) {
                    throw new FormError("You are already a member!");
                }
                $group->join($member);
                $msg = "You are now a member of '{$group->name}' group";
                break;
            case 'delete':
                if (!$group->isAdmin($member)) {
                    throw new FormError("Only admin can delete a group!");
                }

                $group->delete();
                $msg = "Group '{$group->name}' no longer exists.";
                break;
        }
    } catch (PDOException $e) {
        throw new FormError($e->getMessage());
    }
}
