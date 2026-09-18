<?php

class User
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    /* Get User Details */

    public function getUser($user_id)
    {
        $sql = "SELECT *
                FROM users
                WHERE user_id='$user_id'";

        $result = mysqli_query($this->conn, $sql);

        if(mysqli_num_rows($result)>0)
        {
            return mysqli_fetch_assoc($result);
        }

        return false;
    }


    /* Update User Details */

    public function updateUser(
        $user_id,
        $name,
        $email,
        $phone
    )
    {
        $sql = "UPDATE users
                SET name='$name',
                    email='$email',
                    phone='$phone'
                WHERE user_id='$user_id'";

        return mysqli_query($this->conn, $sql);
    }
}

?>