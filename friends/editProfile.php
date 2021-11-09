<?php

require_once __DIR__ . "/../classes/init.php";

require_once __DIR__ . "/../post/Post.php";
require_once __DIR__ . "/../forms/Validator.php";

$validator = new Validator([], get_object_vars($me));

$user_fields = [
    "fname", "lname", "dob", "sex", "username", "about", "profile_pic", "address"
];

$validator->methodPost(function (Validator $validator) use ($ROOT_DIR, $me) {
    $validator->addRules([
        "fname" => ["min_length" => 2],
        "lname" => [],
        "email" => ["email", "unique" => ["users", "email", "email", $me->email]],
        "dob" => [],
        "sex" => ["in" => ["male", "female"]],
        "username" => ["min_length" => 1],
        "about" => [],
        "address" => []
    ])->addData($_POST)->validate();



    $validator->isValid(function (Validator $validator) use (&$me) {
        $valid_data = array_filter(
            $validator->valid_data,
            function ($key) use ($validator, $me) {
                $changed = $validator->valid_data[$key] !== get_object_vars($me)[$key];
                $allowed = in_array($key, $GLOBALS['user_fields']);
                return $changed && $allowed;
            },
            ARRAY_FILTER_USE_KEY
        );

        if (!count($valid_data)) {
            $validator->setSuccessMsg("Nothing to be updated");
            return;
        }
        $me = $me->updateProperties($valid_data) ?: $me;
        $validator->setSuccessMsg("User updated :)")->redirect(current_url());
    });

    $validator->isInvalid(function (Validator $validator) {
        $validator->setMainError("Invalid data")->redirect(current_url());
    });
});

list($errors, $data, $errorClass, $mainError, $msg, $csrf) = $validator->helpers();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image" href="../assets/images/viachat.png">
    <title>Profile</title>
</head>

<body>
    <div class="container">
        <?php require_once __DIR__ . "/../menu/menu.php"; ?>
        <div class="mainFormContainer">

            <form action="" method="post" class="mainForm" enctype="multipart/form-data">
                <div class="formHeader">
                    <a href="#!" class="formBrand">ViaChat</a>
                    <h2>Edit profile</h2>
                </div>
                <?php echo $msg(); ?>
                <?php echo $mainError(); ?>
                <div class="formBody">
                    <?php echo $csrf(); ?>
                    <div class="mainInput split">
                        <div class="mainInput__split  <?php echo $errorClass('fname'); ?>">
                            <Label for="edit_firstname">Firstname</Label>
                            <input value="<?php echo $data("fname"); ?>" name="fname" placeholder="Type firstname.." type="text" id="edit_firstname" />
                            <?php echo $errors('fname'); ?>
                        </div>
                        <div class="mainInput__split  <?php echo $errorClass('lname'); ?>">
                            <Label for="edit_lastname">Lastname</Label>
                            <input value="<?php echo $data("lname"); ?>" name="lname" placeholder="Type lastname.." type="text" id="edit_lastname" />
                            <?php echo $errors('lname'); ?>
                        </div>
                    </div>
                    <div class="mainInput <?php echo $errorClass('email'); ?>">
                        <Label for="edit_email">E-mail</Label>
                        <input value="<?php echo $data("email"); ?>" name="email" placeholder="Type e-mail.." type="text" id="edit_email" />
                        <?php echo $errors('email'); ?>
                    </div>
                    <div class="mainInput <?php echo $errorClass('username'); ?>">
                        <Label for="edit_username">Username</Label>
                        <input value="<?php echo $data("username"); ?>" name="username" placeholder="Type username.." type="text" id="edit_username" />
                        <?php echo $errors('username'); ?>
                    </div>
                    <div class="mainInput <?php echo $errorClass('about'); ?>">
                        <label for="edit_about">About</label>
                        <textarea name="about" placeholder="Tell us about u?" id="edit_about"><?php echo $data("about"); ?></textarea>
                        <?php echo str_replace('_', ' ', $errors('about')); ?>
                    </div>
                    <div class="mainInput <?php echo $errorClass('address'); ?>">
                        <label for="edit_address">Address</label>
                        <input value="<?php echo $data("address"); ?>" name="address" placeholder="Enter address.." type="text" id="edit_address" />
                        <?php echo str_replace('_', ' ', $errors('address')); ?>
                    </div>
                    <!-- <div class="mainInput <?php echo $errorClass('username'); ?>"> -->
                    <div class="mainInput">
                        <label for="edit_dob">Date of birth</label>
                        <input value="<?php echo $data("dob"); ?>" name="dob" placeholder="Type dob.." type="date" id="edit_dob" />
                        <?php echo $errors('dob'); ?>
                    </div>
                    <div class="mainInput">
                        <p class="label">Sex</p>
                        <label for="sex_male">Male</label>
                        <input <?php echo $me->sex === "male" ? "checked" : ""; ?> value="male" name="sex" type="radio" id="sex_male" />
                        <label for="sex_female">Female</label>
                        <input <?php echo $me->sex === "female" ? "checked" : ""; ?> value="female" name="sex" type="radio" id="sex_female" />
                        <?php echo str_replace("sex", "sex", $errors('sex')); ?>
                    </div>
                </div>
                <div class="mainInput">
                        <p class="label"><a href="../auth/change_password.php">Change Password</a></p>
                        
                    </div>
                <button>Save</button>
            </form>
        </div>
    </div>
</body>

</html>