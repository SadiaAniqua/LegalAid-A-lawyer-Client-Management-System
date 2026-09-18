<?php

class Lawyer
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    /* =========================================
       Get Lawyer Profile
       ========================================= */

    public function getProfile($user_id)
    {
        $sql = "SELECT lawyer_profiles.*,
                users.name,
                users.email,
                users.phone,
                users.status,
                specializations.name AS specialization_name

                FROM lawyer_profiles

                JOIN users
                ON lawyer_profiles.user_id=users.user_id

                JOIN specializations
                ON lawyer_profiles.specialization_id=
                   specializations.specialization_id

                WHERE lawyer_profiles.user_id='$user_id'";

        $result = mysqli_query($this->conn, $sql);

        if(mysqli_num_rows($result)>0)
        {
            return mysqli_fetch_assoc($result);
        }

        return false;
    }


    /* =========================================
       Get All Specializations
       ========================================= */

    public function getSpecializations()
    {
        $sql = "SELECT *
                FROM specializations
                ORDER BY name";

        return mysqli_query($this->conn, $sql);
    }


    /* =========================================
       Search Lawyers
       ========================================= */

    public function searchLawyers(
        $search = "",
        $specialization_id = ""
    )
    {
        $sql = "SELECT lawyer_profiles.*,
                users.name,
                specializations.name AS specialization_name

                FROM lawyer_profiles

                JOIN users
                ON lawyer_profiles.user_id=users.user_id

                JOIN specializations
                ON lawyer_profiles.specialization_id=
                   specializations.specialization_id

                WHERE lawyer_profiles.verification_status='verified'";


        /* Search by Lawyer Name */

        if($search != "")
        {
            $search = mysqli_real_escape_string(
                $this->conn,
                $search
            );

            $sql .= " AND users.name LIKE '%$search%'";
        }


        /* Search by Specialization */

        if($specialization_id != "")
        {
            $specialization_id = mysqli_real_escape_string(
                $this->conn,
                $specialization_id
            );

            $sql .= " AND lawyer_profiles.specialization_id='$specialization_id'";
        }


        /* Sort Lawyers */

        $sql .= " ORDER BY users.name ASC";


        return mysqli_query(
            $this->conn,
            $sql
        );
    }


    /* =========================================
       Create Lawyer Profile
       ========================================= */

    public function createProfile(
        $user_id,
        $specialization_id,
        $experience,
        $chamber_name,
        $address,
        $bio,
        $consultation_fee
    )
    {
        $sql = "INSERT INTO lawyer_profiles
                (
                    user_id,
                    specialization_id,
                    experience,
                    chamber_name,
                    address,
                    bio,
                    consultation_fee,
                    verification_status
                )

                VALUES
                (
                    '$user_id',
                    '$specialization_id',
                    '$experience',
                    '$chamber_name',
                    '$address',
                    '$bio',
                    '$consultation_fee',
                    'pending'
                )";

        return mysqli_query(
            $this->conn,
            $sql
        );
    }


    /* =========================================
       Update Lawyer Profile
       ========================================= */

    public function updateProfile(
        $user_id,
        $specialization_id,
        $experience,
        $chamber_name,
        $address,
        $bio,
        $consultation_fee
    )
    {
        $sql = "UPDATE lawyer_profiles

                SET
                    specialization_id='$specialization_id',
                    experience='$experience',
                    chamber_name='$chamber_name',
                    address='$address',
                    bio='$bio',
                    consultation_fee='$consultation_fee'

                WHERE user_id='$user_id'";

        return mysqli_query(
            $this->conn,
            $sql
        );
    }

}

?>