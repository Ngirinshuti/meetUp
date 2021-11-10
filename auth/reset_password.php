<?php
// user login script 

require_once __DIR__ . "/unauthenticate.php";
require_once __DIR__ . "/../forms/Validator.php";
require_once __DIR__ . "/../classes/Session.php";

$validator = new Validator();
list($errors, $data, $errorClass, $mainError, $msg, $csrf) = $validator->helpers();


$validator->methodPost(function (Validator $validator)
{
    $validator->addRules([
        "email" => ["not_empty", "email"]
    ]);

    $validator->addData($_POST)->validate();
});

$validator->isValid(function(Validator $validator) {
    $user = User::findOne(email: $validator->valid_data['email']);
    if (!$user) {
        $validator->setMainError("User not found");
        return;
    }

    $token = $user->createResetToken();
    $url = getUrl("/auth/reset_password_callback.php?token=$token");
    $link = "<a style='cursor: pointer;
    border: 0;
    background-color: #7369ee;
    color: white;
    text-transform: uppercase;
    padding: 0.65rem 1rem;
    border-radius: 5px;
    font-weight: 500;
    
    transition: background-color 0.3s;
    position: relative;
    overflow: hidden;
    transform: translate(10px, 10px);
    margin: .5rem;
    font-size: 0.9rem;' href='{$_SERVER['HTTP_ORIGIN']}$url'>Click to reset password</a>";

    Mail::sendReset($user->email, $link);
    
    $validator->setSuccessMsg("Email verification was link sent to your email");
});

$validator->isInvalid(function(Validator $validator) {
    $validator->setMainError("Please correct errors below");
});

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo getUrl("/css/main.css") ?>">
    <script src="<?php echo getUrl("/js/theme.js"); ?>" defer></script>
    <title>Reset Password</title>
</head>

<body>
    <div class="mainFormContainer">

        <form action="" method="post" class="mainForm">
            <div class="formHeader">
                <a href="#!" class="formBrand">ViaChat</a>
                <h2>Password Reset</h2>
            </div>
            <?php echo $msg(); ?>
            <?php echo $mainError(); ?>
            <div class="formBody">
                <?php echo $csrf(); ?>
                <div class="mainInput">
                    <label for="reset_email">Your email</label>
                    <input name="email" placeholder="Enter email.." class="<?php echo $errorClass("email"); ?>" type="email" value="<?php echo $data("email"); ?>" id="reset_email">
                    <?php echo $errors("email") ?>
                </div>
            </div>
            <button>Send email Reset Link</button>
            <a class="btn" href="<?php echo getUrl("/auth/index.php"); ?>">Back to Login</a>
        </form>
    </div>
</body>

</html>