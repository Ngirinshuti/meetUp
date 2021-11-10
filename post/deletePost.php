<?php
// home feed file

require_once __DIR__ . "/../classes/init.php";
require_once __DIR__ . "/../forms/Validator.php";
require_once __DIR__ . "/../post/Post.php";


$validator = new Validator([
    "id" => ['exists' => ['posts']]
]);


$validator->methodPost(function (Validator $validator) {
    $validator->addData($_POST)->validate();

    $validator->isValid(function (Validator $validator) {
        $post = Post::findOne((int) $validator->valid_data['id']);

        if (is_file(toDir("/post/videos/{$post->video}"))) {
            unlink(toDir("/post/videos/{$post->video}"));
        }

        if (is_file(toDir("/post/images/{$post->image}"))) {
            unlink(toDir("/post/images/{$post->image}"));
        }

        $post->delete();
        $url = strpos(getBackUrl(), getUrl('/friends/profile.php')) ? getBackUrl() :  getUrl('/post/home.php');
        $validator->setSuccessMsg("Post Deleted!")->redirect($url);
    });

    $validator->isInvalid(function (Validator $validator) {
        $url = strpos(getBackUrl(), getUrl('/friends/profile.php')) ? getBackUrl() :  getUrl('/post/home.php');
        $validator->setMainError("Something, went wrong!")->redirect($url);
    });
});
