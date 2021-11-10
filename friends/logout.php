<?php 
// logout file

require_once __DIR__ . "/../classes/init.php";
require_once __DIR__ . "/../forms/Validator.php";

$validator = new Validator();

$validator->methodPost(function (Validator $validator) use ($me) {
    $validator->validate();

    $validator->isValid(function (Validator $validator) use ($me){
        $me->setProperty("status", "offline");
        $me->setProperty("last_seen", "CURRENT_TIMESTAMP()", raw: true);
        Auth::logout();
        $validator->redirect(getUrl("/"));
    });

    $validator->isInvalid(function (Validator $validator) {
        $validator->setMainError("Invalid logout");
        if (Session::has('url.last.full')){
            $validator->redirect(Session::get('url.last.full'));
        }
        $validator->redirect(getUrl("/"));
    });

    // header("Location: $ROOT_URL/index.php");
});

