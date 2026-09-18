<?php

include(__DIR__ . "/../db.php");
include(__DIR__ . "/../models/Lawyer.php");

$lawyerModel = new Lawyer($conn);


/* =====================================================
   COMMON VARIABLES
   ===================================================== */

$message = "";

$user_id = isset($_SESSION["id"])
    ? $_SESSION["id"]
    : 0;


/* =====================================================
   LAWYER PROFILE
   ===================================================== */

if(isset($_POST["save"]))
{
    $specialization_id = $_POST["specialization_id"];
    $experience = $_POST["experience"];
    $chamber_name = $_POST["chamber_name"];
    $address = $_POST["address"];
    $bio = $_POST["bio"];
    $consultation_fee = $_POST["consultation_fee"];


    /* Check Existing Profile */

    $existingProfile =
        $lawyerModel->getProfile($user_id);


    if($existingProfile != false)
    {
        /* Update Profile */

        if(
            $lawyerModel->updateProfile(
                $user_id,
                $specialization_id,
                $experience,
                $chamber_name,
                $address,
                $bio,
                $consultation_fee
            )
        )
        {
            $message =
                "Profile Updated Successfully";
        }
        else
        {
            $message =
                "Profile Update Failed";
        }
    }
    else
    {
        /* Create Profile */

        if(
            $lawyerModel->createProfile(
                $user_id,
                $specialization_id,
                $experience,
                $chamber_name,
                $address,
                $bio,
                $consultation_fee
            )
        )
        {
            $message =
                "Profile Created Successfully";
        }
        else
        {
            $message =
                "Profile Creation Failed";
        }
    }
}


/* Get Current Lawyer Profile */

$profile =
    $lawyerModel->getProfile($user_id);


/* Get Specializations */

$specializations =
    $lawyerModel->getSpecializations();


/* =====================================================
   FIND LAWYER SEARCH
   ===================================================== */

$search = "";

$specialization_id = "";


/* Get search value */

if(isset($_GET["search"]))
{
    $search = $_GET["search"];
}


/* Get specialization */

if(isset($_GET["specialization_id"]))
{
    $specialization_id =
        $_GET["specialization_id"];
}


/* Search Lawyers */

$result =
    $lawyerModel->searchLawyers(
        $search,
        $specialization_id
    );

?>