<?php

class CaseModel
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


    /* Get All Cases */

    public function getLawyerCases($lawyer_id)
    {
        $sql = "SELECT cases.*,
                users.name AS client_name

                FROM cases

                JOIN users
                ON cases.client_id=users.user_id

                WHERE cases.lawyer_id='$lawyer_id'

                ORDER BY cases.case_id DESC";

        return mysqli_query($this->conn, $sql);
    }


    /* Update Case Status */

    public function updateStatus($case_id, $status, $lawyer_id)
    {
        $sql = "UPDATE cases
                SET status='$status'
                WHERE case_id='$case_id'
                AND lawyer_id='$lawyer_id'";

        return mysqli_query($this->conn, $sql);
    }


    /* Check Accepted Appointment */

    public function getAcceptedAppointment(
        $appointment_id,
        $lawyer_id
    )
    {
        $sql = "SELECT *
                FROM appointments
                WHERE appointment_id='$appointment_id'
                AND lawyer_id='$lawyer_id'
                AND status='accepted'";

        $result = mysqli_query($this->conn, $sql);

        if(mysqli_num_rows($result) > 0)
        {
            return mysqli_fetch_assoc($result);
        }

        return false;
    }


    /* Create Case */

    public function createCase(
        $client_id,
        $lawyer_id,
        $appointment_id,
        $case_title,
        $case_type,
        $case_description
    )
    {
        $sql = "INSERT INTO cases
                (
                    client_id,
                    lawyer_id,
                    appointment_id,
                    case_title,
                    case_type,
                    case_description,
                    status
                )

                VALUES
                (
                    '$client_id',
                    '$lawyer_id',
                    '$appointment_id',
                    '$case_title',
                    '$case_type',
                    '$case_description',
                    'Open'
                )";

        return mysqli_query($this->conn, $sql);
    }


    /* Get Accepted Appointments */

    public function getAcceptedAppointments($lawyer_id)
    {
        $sql = "SELECT appointments.*,
                users.name AS client_name

                FROM appointments

                JOIN users
                ON appointments.client_id=users.user_id

                WHERE appointments.lawyer_id='$lawyer_id'
                AND appointments.status='accepted'

                ORDER BY appointments.appointment_date,
                appointments.appointment_time";

        return mysqli_query($this->conn, $sql);
    }
	/* Get Client Cases */

public function getClientCases($client_id)
{
    $sql = "SELECT cases.*,
            users.name AS lawyer_name

            FROM cases

            JOIN lawyer_profiles
            ON cases.lawyer_id=lawyer_profiles.lawyer_id

            JOIN users
            ON lawyer_profiles.user_id=users.user_id

            WHERE cases.client_id='$client_id'

            ORDER BY cases.case_id DESC";

    return mysqli_query($this->conn, $sql);
}
}

?>