<?php

include(__DIR__ . "/../db.php");
include(__DIR__ . "/../models/LawyerAppointment.php");

$lawyerAppointment = new LawyerAppointment($conn);

$lawyer_user_id = $_SESSION["id"];

$message = "";


/* Get Lawyer ID */

$lawyer_id = $lawyerAppointment->getLawyerId(
    $lawyer_user_id
);


/* Accept Appointment */

if(isset($_GET["accept"]))
{
    $appointment_id = $_GET["accept"];

    if(
        $lawyerAppointment->acceptAppointment(
            $appointment_id,
            $lawyer_id
        )
    )
    {
        $message = "Appointment Accepted";
    }
    else
    {
        $message = "Appointment Accept Failed";
    }
}


/* Reject Appointment */

if(isset($_GET["reject"]))
{
    $appointment_id = $_GET["reject"];

    if(
        $lawyerAppointment->rejectAppointment(
            $appointment_id,
            $lawyer_id
        )
    )
    {
        $message = "Appointment Rejected";
    }
    else
    {
        $message = "Appointment Reject Failed";
    }
}


/* Get Appointments */

$result = $lawyerAppointment->getLawyerAppointments(
    $lawyer_id
);

?>