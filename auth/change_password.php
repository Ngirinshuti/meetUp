<?php 

require_once __DIR__ . "/../classes/init.php";
require_once __DIR__ . "/../forms/Validator.php";
require_once __DIR__ . "/User.php";
$user = isset($_GET['user']) ? User::findOne($_GET['user']) : $me;

if (!$user){
    header("Location: ". getUrl("/404.php?url=" . current_url()));
    exit("Error");
}

$validator = new Validator();
list($errors, $data, $errorClass, $mainError, $msg, $csrf) = $validator->helpers();


$validator->methodPost(
    function (Validator $validator) {
        $me = $GLOBALS['me'];
        
        $validator->addRules([
            "old_password" => ["not_empty", "hash_match" => $me->password],
            "new_password" => ["not_empty", "should_match" => "confirm_password"],
            "confirm_password" => ["required"]
        ])->addData($_POST)->validate();

        if ($validator->valid) {
            $new_password = $validator->data("new_password");
            Auth::currentUser()->changePassword($new_password);
            $validator->setSuccessMsg("Password changed successfully");
        }
    }
);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/main.css">
  <link rel="stylesheet" href="<?php echo getUrl("/css/home.css");  ?>">
  <link rel="icon" type="image" href="../assets/images/viachat.png">
    <title>Change Password</title>
</head>

<body>
<div class="container">
    <?php require '../menu/menu.php'; ?>
    <div class="mainFormContainer">
        <form action="" method="post" class="mainForm">
            <div class="formHeader">
                <a href="#!" class="formBrand">ViaChat</a><h2>Change Password</h2>
            </div>
            <?php echo $msg(); ?>
            <div class="formBody">
                <?php echo $csrf(); ?>
               <div class="mainInput  <?php echo $errorClass('old_password'); ?>">
                    <label for="old_pasword">Old Password</label>
                    <input name="old_password" placeholder="Type old password.." type="password" id="old_pasword" />
                    <?php echo $errors('old_password'); ?>
                </div>
                <div class="mainInput  <?php echo $errorClass('new_password'); ?>">
                    <label for="new_password">New Password</label>
                    <input name="new_password" placeholder="Type new password.." type="password" id="new_password" />
                    <?php echo $errors('new_password'); ?>
                </div>
                  <div class="mainInput  <?php echo $errorClass('confirm_password'); ?>">
                    <label for="confirm_new_password">Confirm New Password</label>
                    <input name="confirm_password" placeholder="Re-type new password.." type="password" id="confirm_new_password" />
                    <?php echo $errors('confirm_password'); ?>
                </div>
        
            </div>
            <button type="submit">Change Password</button>
        </form>
    </div>
</div>
    <script defer>
    </script>
</body>

</html>