<?php

include(__DIR__ . "/../db.php");
include(__DIR__ . "/../models/User.php");

$userModel = new User($conn);

$user_id = $_SESSION["id"];

$message = "";


/* Update Profile */

if(isset($_POST["update"]))
{
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];

    if(
        $userModel->updateUser(
            $user_id,
            $name,
            $email,
            $phone
        )
    )
    {
        $message = "Profile Updated Successfully";
    }
    else
    {
        $message = "Profile Update Failed";
    }
}


/* Get Current User Details */

$user = $userModel->getUser($user_id);

?>