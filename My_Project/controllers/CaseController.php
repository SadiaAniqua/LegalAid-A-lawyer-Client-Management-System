<?php

include(__DIR__ . "/../db.php");
include(__DIR__ . "/../models/Case.php");

$caseModel = new CaseModel($conn);

$lawyer_user_id = $_SESSION["id"];

$message = "";


/* Get Lawyer ID */

$lawyer_id = $caseModel->getLawyerId(
    $lawyer_user_id
);


/* Update Case Status */

if(isset($_POST["update"]))
{
    $case_id = $_POST["case_id"];
    $status = $_POST["status"];

    if(
        $caseModel->updateStatus(
            $case_id,
            $status,
            $lawyer_id
        )
    )
    {
        $message = "Case Status Updated Successfully";
    }
    else
    {
        $message = "Case Status Update Failed";
    }
}


/* Create New Case */

if(isset($_POST["create"]))
{
    $appointment_id = $_POST["appointment_id"];
    $case_title = $_POST["case_title"];
    $case_type = $_POST["case_type"];
    $case_description = $_POST["case_description"];


    $appointment =
        $caseModel->getAcceptedAppointment(
            $appointment_id,
            $lawyer_id
        );


    if($appointment != false)
    {
        $client_id = $appointment["client_id"];

        if(
            $caseModel->createCase(
                $client_id,
                $lawyer_id,
                $appointment_id,
                $case_title,
                $case_type,
                $case_description
            )
        )
        {
            $message = "Case Created Successfully";
        }
        else
        {
            $message = "Case Creation Failed";
        }
    }
    else
    {
        $message = "Invalid Appointment";
    }
}


/* Get Cases */

$result =
    $caseModel->getLawyerCases(
        $lawyer_id
    );


/* Get Accepted Appointments */

$acceptedAppointments =
    $caseModel->getAcceptedAppointments(
        $lawyer_id
    );

?>