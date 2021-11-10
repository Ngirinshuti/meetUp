<?php

/**
 * Configuration file
 */

//  directories
$ROOT_DIR = __DIR__;

// database
$DB_HOST = "localhost";
$DB_NAME = "new_project2";
$DB_USER = "root";
$DB_PASSWORD = "";

// email
$MAIL_SENDER = "ishimwedeveloper@gmail.com";

// urls
$ROOT_URL = "/new";
$LOGIN_URL = $ROOT_URL . "/auth/index.php";

// security
$PASSWORD_SALT = "salting_string@12345";

function getUrl($sub_url = "") {
    $ROOT_URL = $GLOBALS['ROOT_URL'];
    return $ROOT_URL . $sub_url;
};

function toDir($sub_dir = "") {
    return $GLOBALS['ROOT_DIR'] . $sub_dir;
}

function gotoDir($sub_dir = "") {
    $ROOT_DIR = $GLOBALS['ROOT_DIR'];

    echo $ROOT_DIR . $sub_dir;
};

function current_url_full() {
    return $_SERVER['REQUEST_URI'];
}

function current_url() {
    return explode("?", current_url_full())[0];
}
