<?php

class Appointment
{
    private $conn;


    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    /* Get all appointments of a client */

    public function getClientAppointments($client_id)
    {
        $client_id = mysqli_real_escape_string(
            $this->conn,
            $client_id
        );

        $sql = "SELECT appointments.*,
                       users.name AS lawyer_name,
                       specializations.name AS specialization_name

                FROM appointments

                JOIN lawyer_profiles
                ON appointments.lawyer_id =
                   lawyer_profiles.lawyer_id

                JOIN users
                ON lawyer_profiles.user_id =
                   users.user_id

                JOIN specializations
                ON lawyer_profiles.specialization_id =
                   specializations.specialization_id

                WHERE appointments.client_id='$client_id'

                ORDER BY appointments.appointment_date DESC";

        return mysqli_query($this->conn, $sql);
    }


    /* Cancel Appointment */

    public function cancelAppointment(
        $appointment_id,
        $client_id
    )
    {
        $appointment_id = mysqli_real_escape_string(
            $this->conn,
            $appointment_id
        );

        $client_id = mysqli_real_escape_string(
            $this->conn,
            $client_id
        );

        $sql = "UPDATE appointments

                SET status='cancelled'

                WHERE appointment_id='$appointment_id'

                AND client_id='$client_id'

                AND status IN ('pending','accepted')";

        return mysqli_query($this->conn, $sql);
    }


    /* Reschedule Appointment */

    public function rescheduleAppointment(
        $appointment_id,
        $client_id,
        $new_date,
        $new_time
    )
    {
        $appointment_id = mysqli_real_escape_string(
            $this->conn,
            $appointment_id
        );

        $client_id = mysqli_real_escape_string(
            $this->conn,
            $client_id
        );

        $new_date = mysqli_real_escape_string(
            $this->conn,
            $new_date
        );

        $new_time = mysqli_real_escape_string(
            $this->conn,
            $new_time
        );

        $sql = "UPDATE appointments

                SET appointment_date='$new_date',
                    appointment_time='$new_time',
                    status='pending'

                WHERE appointment_id='$appointment_id'

                AND client_id='$client_id'

                AND status='accepted'";

        return mysqli_query($this->conn, $sql);
    }
}

?>