<?php

session_start();

include("../db.php");

if(!isset($_SESSION["id"]))
{
    exit();
}


$search="";

$specialization_id="";


if(isset($_POST["search"]))
{
    $search=$_POST["search"];
}


if(isset($_POST["specialization_id"]))
{
    $specialization_id=$_POST["specialization_id"];
}


$sql="SELECT lawyer_profiles.*,
users.name,
specializations.name AS specialization_name

FROM lawyer_profiles

JOIN users

ON lawyer_profiles.user_id=users.user_id

JOIN specializations

ON lawyer_profiles.specialization_id=
specializations.specialization_id

WHERE lawyer_profiles.verification_status='verified'";


if($search!="")
{
    $sql=$sql."
    AND users.name LIKE '%$search%'";
}


if($specialization_id!="")
{
    $sql=$sql."
    AND lawyer_profiles.specialization_id=
    '$specialization_id'";
}


$result=mysqli_query($conn,$sql);


if(mysqli_num_rows($result)>0)
{

while($row=mysqli_fetch_assoc($result))
{

?>

<tr>

<td>

<?php echo $row["name"]; ?>

</td>

<td>

<?php echo $row["specialization_name"]; ?>

</td>

<td>

<?php echo $row["experience"]; ?> years

</td>

<td>

<?php echo $row["chamber_name"]; ?>

</td>

<td>

<?php echo $row["consultation_fee"]; ?>

</td>

<td>

<a
href="bookAppointment.php?lawyer_id=<?php echo $row["lawyer_id"]; ?>"
>

Book Appointment

</a>

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

No Lawyer Found

</center>

</td>

</tr>

<?php

}

?>