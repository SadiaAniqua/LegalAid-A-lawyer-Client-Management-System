<?php

class LawyerAppointment
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    /* Get Lawyer ID */

    public function getLawyerId($user_id)
    {
        $sql = "SELECT lawyer_id
                FROM lawyer_profiles
                WHERE user_id='$user_id'";

        $result = mysqli_query($this->conn, $sql);

        if(mysqli_num_rows($result) > 0)
        {
            $row = mysqli_fetch_assoc($result);

            return $row["lawyer_id"];
        }

        return 0;
    }


    /* Accept Appointment */

    public function acceptAppointment($appointment_id, $lawyer_id)
    {
        $sql = "UPDATE appointments
                SET status='accepted'
                WHERE appointment_id='$appointment_id'
                AND lawyer_id='$lawyer_id'
                AND status='pending'";

        return mysqli_query($this->conn, $sql);
    }


    /* Reject Appointment */

    public function rejectAppointment($appointment_id, $lawyer_id)
    {
        $sql = "UPDATE appointments
                SET status='rejected'
                WHERE appointment_id='$appointment_id'
                AND lawyer_id='$lawyer_id'
                AND status='pending'";

        return mysqli_query($this->conn, $sql);
    }


    /* Get Lawyer Appointments */

    public function getLawyerAppointments($lawyer_id)
    {
        $sql = "SELECT appointments.*,
                users.name AS client_name

                FROM appointments

                JOIN users
                ON appointments.client_id=users.user_id

                WHERE appointments.lawyer_id='$lawyer_id'

                ORDER BY appointments.appointment_date,
                appointments.appointment_time";

        return mysqli_query($this->conn, $sql);
    }
}

?>