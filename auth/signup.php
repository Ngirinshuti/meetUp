<?php
// signup script
require_once __DIR__ . "/./unauthenticate.php";
require_once __DIR__ . "/./Auth.php";
require_once __DIR__ . "/../forms/Validator.php";

$validator = new Validator();
list($errors, $data, $errorClass, $mainError, $msg, $csrf) = $validator->helpers();


$validator->methodPost(
    function (Validator $validator) {
        $validator->addRules(
            [
                "fname" => ["not_empty" => true],
                "lname" => ["not_empty" => true],
                "username" => ["not_empty" => true],
                "email" => ["not_empty" => true, "email", "unique" => ['users', 'email']],
                "password" => ["not_empty" => true, 'should_match' => 'confirm_password'],
            ]
        )->addData($_POST)->validate();

        if ($validator->valid) {
            try {
                $user = Auth::signup(...$validator->valid_data);
                if (!$user) {
                    return new AuthException("Something, went wrong! try again later :(");
                }

                
                if (Auth::sendVerficationCode($user)) {
                    $_SESSION['verify_email'] = $user->email;
                    $validator->setSuccessMsg("Account was created! Verification code sent, check email.");
                    $validator->redirect(getUrl("/auth/verify_email.php"));
                }

            } catch (AuthException $e) {
                $validator->setMainError($e->getMessage())->redirect(current_url_full());
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
    <title>Signup</title>
</head>

<body>
    <div class="mainFormContainer">

        <form action="" method="post" class="mainForm">
            <div class="formHeader">
                <a href="#!" class="formBrand">ViaChat</a>
                <h2>Signup</h2>
            </div>
            <?php echo $msg(); ?>
            <?php echo $mainError(); ?>
            <div class="formBody">
                <?php echo $csrf(); ?>
                <div class="mainInput split">
                    <div class="mainInput__split">
                        <Label for="signup_firstname">Firstname</Label>
                        <input value="<?php echo $data("fname"); ?>" name="fname" placeholder="Type firstname.." type="text" id="signup_firstname" />
                        <?php echo str_replace("Fname", "Firstname", $errors('fname')); ?>
                    </div>
                    <div class="mainInput__split">
                        <Label for="signup_lastname">Lastname</Label>
                        <input value="<?php echo $data("lname"); ?>" name="lname" placeholder="Type lastname.." type="text" id="signup_lastname" />
                        <?php echo str_replace("Lname", "Lastname", $errors('lname')); ?>
                    </div>
                </div>
                <div class="mainInput <?php echo $errorClass('email'); ?>">
                    <Label for="signup_email">E-mail</Label>
                    <input value="<?php echo $data("email"); ?>" name="email" placeholder="Type e-mail.." type="text" id="signup_email" />
                    <?php echo $errors('email'); ?>
                </div>
                <div class="mainInput <?php echo $errorClass('username'); ?>">
                    <Label for="signup_username">Username</Label>
                    <input value="<?php echo $data("username"); ?>" name="username" placeholder="Type username.." type="text" id="signup_username" />
                    <?php echo $errors('username'); ?>
                </div>
                <div class="mainInput split">

                    <div class="mainInput__split <?php echo $errorClass('password'); ?>">
                        <label for="signup_password">Password</label>
                        <input name="password" placeholder="Type password.." type="password" id="signup_password" />
                        <?php echo str_replace('_', ' ', $errors('password')); ?>
                    </div>
                    <div class="mainInput__split <?php echo $errorClass('confirm_password'); ?>">
                        <label for="signup_confirm_password">Confirm Password</label>
                        <input name="confirm_password" placeholder="Re-type password.." type="password" id="signup_confirm_password" />
                        <?php echo str_replace('_', ' ', $errors('confirm_password')); ?>
                    </div>
                </div>
            </div>
            <p class="formText">Already have an account? <a href="./index.php" class="formLink">login</a></p>
            <button>Signup</button>
        </form>
    </div>
    <script defer>
        // form placeholder
    </script>
</body>

</html>