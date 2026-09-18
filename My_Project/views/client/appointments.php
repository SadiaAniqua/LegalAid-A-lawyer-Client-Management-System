<?php

session_start();

if(!isset($_SESSION["id"]))
{
    header("Location: ../../login.php");
    exit();
}

if($_SESSION["role"]!="client")
{
    header("Location: ../../dashboard.php");
    exit();
}

include("../../controllers/AppointmentController.php");

$username = $_SESSION["username"];
$activePage = 'appointments';

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Appointments — LegalAid</title>
<link rel="icon" href="../../logo/favicon-32.png">
<link rel="apple-touch-icon" href="../../logo/apple-touch-icon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="appointments.css">
</head>
<body>

<div class="app">

  <?php include("../partials/sidebar.php"); ?>

  <main class="main">

    <header class="topbar">
      <div>
        <a class="back-link" href="../../dashboard.php">
          <?php echo icon('back'); ?>
          Back to Dashboard
        </a>
        <h1>My Appointments</h1>
        <p class="subtitle">Review, cancel, or reschedule your bookings.</p>
      </div>
      <div class="account">
        <span class="account-name"><?php echo htmlspecialchars($username); ?></span>
        <div class="avatar"><?php echo strtoupper(substr($username, 0, 1)); ?></div>
      </div>
    </header>

    <?php if (!empty($message)): ?>
    <div class="message message-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <section class="results-card">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Lawyer</th>
            <th>Specialization</th>
            <th>Date</th>
            <th>Time</th>
            <th>Reason</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?php echo htmlspecialchars($row["appointment_id"]); ?></td>
              <td>
                <div class="person-cell">
                  <span class="person-avatar"><?php echo strtoupper(substr($row["lawyer_name"], 0, 1)); ?></span>
                  <?php echo htmlspecialchars($row["lawyer_name"]); ?>
                </div>
              </td>
              <td><span class="tag"><?php echo htmlspecialchars($row["specialization_name"]); ?></span></td>
              <td><?php echo htmlspecialchars($row["appointment_date"]); ?></td>
              <td><?php echo htmlspecialchars($row["appointment_time"]); ?></td>
              <td><?php echo htmlspecialchars($row["reason"]); ?></td>
              <td><span class="tag"><?php echo htmlspecialchars($row["status"]); ?></span></td>
              <td class="action-cell">
                <div class="action-stack">
                  <a class="button button-ghost" href="../../client/appointmentDetails.php?id=<?php echo urlencode($row["appointment_id"]); ?>">View Details</a>
                  <a class="button button-ghost" href="../../client/lawyerDetails.php?id=<?php echo urlencode($row["lawyer_id"]); ?>">View Lawyer</a>
                  <?php if ($row["status"] == "pending" || $row["status"] == "accepted"): ?>
                  <a class="button button-danger" href="?cancel=<?php echo urlencode($row["appointment_id"]); ?>">Cancel</a>
                  <?php endif; ?>
                  <?php if ($row["status"] == "accepted"): ?>
                  <a class="button button-small" href="../../client/reschedule.php?id=<?php echo urlencode($row["appointment_id"]); ?>">Reschedule</a>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="8" class="empty-state">No appointments found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </section>

    <footer class="footer">
      &copy; <?php echo date("Y"); ?> LegalAid. All rights reserved.
    </footer>

  </main>
</div>

</body>
</html>