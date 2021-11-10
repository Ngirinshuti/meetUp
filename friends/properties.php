<?php

require_once "../classes/init.php";

$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : "";
$action = isset($_POST["action"]) ? $_POST["action"] : "";
$msg = "";

if (isset($_FILES['image']['name'])) {
    $page = $_POST["page"];
    $separate = explode(".", $_FILES["image"]["name"]);
    $ext = end($separate);
    $rand = rand(100, 888);
    $name = $rand . "." . $ext;
    $location = "../images/" . $name;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $location)) {
        $result = $user_obj->update_property("profile_pic", $name);
        throw_result($result, $page);
    } else {
        $result = ["error" => "Problem uploading image file try again later!"];
        throw_result($result, $page);
    }
}

if (isset($_POST["about"])) {
    $page = $_POST["page"];
    $about = test_input($_POST["about"]);
    $result = $user_obj->update_property("about", $about);
    throw_result($result, $page);
}

if (isset($_POST["address"])) {
    $page = $_POST["page"];
    $address = test_input($_POST["address"]);
    $result = $user_obj->update_property("address", $address);
    throw_result($result, $page);
}

function throw_result($result, $page, $other= "")
{
    $other = !empty($other) ? "&$other" : "";
    
    if (!is_array($result)){
        $result = [];
    }

    if (isset($result["error"])) {
        header("location: $page?error={$result["error"]}$other");
    } elseif (isset($result["info"])) {
        header("location: $page?info={$result["info"]}$other");
    } else {
        $other = !empty($other) ? substr($other, 1) : "";
        header("location: $page?$other");
    }
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
