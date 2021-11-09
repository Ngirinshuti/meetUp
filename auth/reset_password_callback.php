<?php

require_once __DIR__ . "/../auth/unauthenticate.php";
require_once __DIR__ . "/../forms/Validator.php";
require_once __DIR__ . "/User.php";

if (!function_exists("getUrl")) {
    include "../config.php";
}

if (!isset($_REQUEST['token'])) {
    exit("Access denied!");
}
$user = User::getByResetToken($_GET['token']);

if (!$user) {
    exit("Invalid token!");
}

$validator = new Validator();
list($errors, $data, $errorClass, $mainError, $msg, $csrf) = $validator->helpers();

$validator->methodPost(
    function (Validator $validator) use ($user) {
        $validator->addRules([
            "new_password" => ["not_empty", "should_match" => "confirm_password"],
            "confirm_password" => ["required"]
        ])->addData($_POST)->validate();

        if ($validator->valid) {
            $new_password = $validator->data("new_password");
            $user->markTokenAsVerified($_POST['token']);
            $user->changePassword($new_password);
            Session::set('forms.success.msg', 'Password changed successfully');
            header("Location: " . getUrl("/auth/index.php"));
            exit();
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
        <div class="mainFormContainer">
            <form action="" method="post" class="mainForm">
                <div class="formHeader">
                    <a href="#!" class="formBrand">ViaChat</a>
                    <h2>Change Password</h2>
                </div>
                <?php echo $msg(); ?>
                <div class="formBody">
                    <?php echo $csrf(); ?>
                    <input type="hidden" name="token" value="<?php echo $_REQUEST['token']; ?>">
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
                <a class="btn" href="<?php echo getUrl("/auth/index.php"); ?>">Login instead</a>
            </form>
        </div>
    </div>
    <script defer>
    </script>
</body>

</html>