<?php


require_once __DIR__ . "/./comment_api.php";
require_once __DIR__ . "/../forms/Validator.php";

$validator = new Validator();

$validator->methodPost(function (Validator $validator) {
    $validator->addRules([
        "comment" => ["not_empty" => true],
        "post_id" => [],
        "username" => []
    ])->addData($_POST)->validate();

    if (!$validator->valid) {
        sendJson(fn () => $validator->getErrors());
    }

    if ($validator->valid) {
        $comment = Comment::create(...$validator->valid_data);

        sendJson(fn() => $comment);
    }

});