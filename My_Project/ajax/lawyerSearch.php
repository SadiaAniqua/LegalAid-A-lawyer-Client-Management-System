<?php

session_start();

include("../db.php");
include("../models/Lawyer.php");

$lawyer = new Lawyer($conn);

$search = "";
$specialization_id = "";

if(isset($_POST["search"]))
{
    $search = $_POST["search"];
}

if(isset($_POST["specialization_id"]))
{
    $specialization_id = $_POST["specialization_id"];
}

$result = $lawyer->searchLawyers(
    $search,
    $specialization_id
);

if (mysqli_num_rows($result) > 0)
{
    while ($row = mysqli_fetch_assoc($result))
    {
?>
<tr>
  <td>
    <div class="person-cell">
      <span class="person-avatar"><?php echo strtoupper(substr($row["name"], 0, 1)); ?></span>
      <?php echo htmlspecialchars($row["name"]); ?>
    </div>
  </td>
  <td><span class="tag"><?php echo htmlspecialchars($row["specialization_name"]); ?></span></td>
  <td><?php echo htmlspecialchars($row["experience"]); ?> years</td>
  <td><?php echo htmlspecialchars($row["chamber_name"]); ?></td>
  <td><?php echo htmlspecialchars($row["consultation_fee"]); ?></td>
  <td class="action-cell">
    <a class="button button-small" href="../client/bookAppointment.php?lawyer_id=<?php echo urlencode($row["lawyer_id"]); ?>">Book Appointment</a>
  </td>
</tr>
<?php
    }
}
else
{
?>
<tr>
  <td colspan="6" class="empty-state">No lawyers found.</td>
</tr>
<?php
}
?>