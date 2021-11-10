<?php
// user login script 

require_once __DIR__ . "/unauthenticate.php";
require_once "Auth.php";
require_once __DIR__ . "/../forms/Validator.php";
require_once __DIR__ . "/../classes/Session.php";

$validator = new Validator();
list($errors, $data, $errorClass, $mainError, $msg, $csrf) = $validator->helpers();

$shouldVerify = false;

$validator->methodPost(
    function (Validator $validator) {
        $validator->addRules(
            [
                "username" => ["not_empty" => true],
                "password" => ["not_empty" => true],
                "remember_me" => []
            ]
        )->addData($_POST)->validate();

        if ($validator->valid) {
            try {
                $user = Auth::login(...$validator->valid_data);
                if ($user) {

                    if (!$user->verified) {
                        Auth::sendVerficationCode($user);
                        Session::set("verify_email", $user->email);
                        header("Location: ./verify_email.php?msg=Please verify email first");
                        exit();
                    }

                    // set cookies
                    if (isset($validator->valid_data["remember_me"])) {
                        Auth::remember($user->username);
                    }

                    Auth::authenticate($user);

                    header("Location: ./");
                }
            } catch (AuthException $e) {
                $validator->setMainError($e->getMessage());
            }
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
    <script src="../js/theme.js" defer></script>
    <title>Login</title>
</head>

<body>
    <div class="mainFormContainer">

        <form action="" method="post" class="mainForm">
            <div class="formHeader">
                <a href="#!" class="formBrand">ViaChat</a>
                <h2>Login</h2>
            </div>
            <?php echo $msg(); ?>
            <?php echo $mainError(); ?>
            <div class="formBody">
                <?php echo $csrf(); ?>
                <div class="mainInput <?php echo $errorClass('username'); ?>">
                    <Label for="login_username">Username or email</Label>
                    <input value="<?php echo $data("username"); ?>" name="username" placeholder="Type username.." type="text" id="login_username" />
                    <?php echo $errors('username'); ?>
                </div>
                <div class="mainInput  <?php echo $errorClass('password'); ?>">
                    <label for="login_password">Password</label>
                    <input name="password" placeholder="Type password.." type="password" id="login_password" />
                    <?php echo $errors('password'); ?>
                </div>
                <div class="mainInput  <?php echo $errorClass('remember_me'); ?>">
                    <input name="remember_me" type="checkbox" id="login_remember_me" />
                    <label for="login_remember_me">Remember me</label>
                    <?php echo $errors('remember_me'); ?>
                </div>

            </div>
            <p class="formText">Don't have account? <a href="./signup.php" class="formLink">signup</a></p>
            <p class="formText"><a href="<?php echo getUrl("/auth/reset_password.php") ?>" class="formLink">Forgot password ?</a></p>
            <button>Login</button>
        </form>
    </div>
    <script defer>
    </script>
</body>

</html>