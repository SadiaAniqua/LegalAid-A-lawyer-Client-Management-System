<?php

include(__DIR__ . "/../db.php");
include(__DIR__ . "/../models/Appointment.php");

$appointment = new Appointment($conn);

$client_id = $_SESSION["id"];

$message = "";


/* Cancel Appointment */

if(isset($_GET["cancel"]))
{
    $appointment_id = $_GET["cancel"];

    if(
        $appointment->cancelAppointment(
            $appointment_id,
            $client_id
        )
    )
    {
        $message = "Appointment Cancelled Successfully";
    }
    else
    {
        $message = "Appointment Cancellation Failed";
    }
}


/* Request Reschedule */

if(isset($_POST["reschedule"]))
{
    $appointment_id = $_POST["appointment_id"];
    $new_date = $_POST["new_date"];
    $new_time = $_POST["new_time"];


    if(
        $appointment->rescheduleAppointment(
            $appointment_id,
            $client_id,
            $new_date,
            $new_time
        )
    )
    {
        $message = "Reschedule Request Sent";
    }
    else
    {
        $message = "Reschedule Request Failed";
    }
}


/* Get Client Appointments */

$result = $appointment->getClientAppointments(
    $client_id
);

?>