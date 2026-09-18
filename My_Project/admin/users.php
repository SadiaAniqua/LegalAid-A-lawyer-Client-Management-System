<?php

session_start();

include("../db.php");

if(!isset($_SESSION["id"]))
{
    header("Location: ../login.php");
}

if($_SESSION["role"]!="admin")
{
    header("Location: ../dashboard.php");
}

$message="";


/* Delete User */

if(isset($_GET["delete"]))
{
    $user_id=$_GET["delete"];

    if($user_id!=$_SESSION["id"])
    {
        $sql="DELETE FROM users
        WHERE user_id='$user_id'";

        if(mysqli_query($conn,$sql))
        {
            $message="User Deleted Successfully";
        }
        else
        {
            $message="User Delete Failed";
        }
    }
    else
    {
        $message="Admin Cannot Delete Own Account";
    }
}

?>

<!DOCTYPE html>

<html>

<head>

<title>Manage Users</title>

</head>

<body>

<h1 align="center">

LegalAid

</h1>

<hr>

<h2 align="center">

Manage Users

</h2>

<center>

<span style="color:green;">

<?php echo $message; ?>

</span>

</center>

<br>

<table border="1" cellpadding="10" align="center">

<tr>

<th>User ID</th>

<th>Name</th>

<th>Email</th>

<th>Phone</th>

<th>Role</th>

<th>Action</th>

</tr>

<?php

$sql="SELECT * FROM users

ORDER BY user_id DESC";

$result=mysqli_query($conn,$sql);

if(mysqli_num_rows($result)>0)
{

while($row=mysqli_fetch_assoc($result))
{

?>

<tr>

<td>

<?php echo $row["user_id"]; ?>

</td>

<td>

<?php echo $row["name"]; ?>

</td>

<td>

<?php echo $row["email"]; ?>

</td>

<td>

<?php echo $row["phone"]; ?>

</td>

<td>

<?php echo $row["role"]; ?>

</td>

<td>

<?php

if($row["user_id"]!=$_SESSION["id"])
{

?>

<a href="users.php?delete=<?php echo $row["user_id"]; ?>">

Delete

</a>

<?php

}
else
{

echo "Admin Account";

}

?>

</td>

</tr>

<?php

}

}

else
{

?>

<tr>

<td colspan="6">

<center>

No Users Found

</center>

</td>

</tr>

<?php

}

?>

</table>

<br><br>

<center>

<a href="dashboard.php">

Back to Admin Dashboard

</a>

</center>

</body>

</html>