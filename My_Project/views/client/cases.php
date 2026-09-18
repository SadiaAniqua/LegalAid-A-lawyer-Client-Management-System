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

include("../../controllers/ClientCaseController.php");

$username = $_SESSION["username"];
$activePage = 'cases';

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Cases — LegalAid</title>
<link rel="icon" href="../../logo/favicon-32.png">
<link rel="apple-touch-icon" href="../../logo/apple-touch-icon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="cases.css">
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
        <h1>My Cases</h1>
        <p class="subtitle">Track the status of cases you're involved in.</p>
      </div>
      <div class="account">
        <span class="account-name"><?php echo htmlspecialchars($username); ?></span>
        <div class="avatar"><?php echo strtoupper(substr($username, 0, 1)); ?></div>
      </div>
    </header>

    <section class="results-card">
      <table>
        <thead>
          <tr>
            <th>Case ID</th>
            <th>Lawyer</th>
            <th>Case Title</th>
            <th>Type</th>
            <th>Description</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?php echo htmlspecialchars($row["case_id"]); ?></td>
              <td>
                <div class="person-cell">
                  <span class="person-avatar"><?php echo strtoupper(substr($row["lawyer_name"], 0, 1)); ?></span>
                  <?php echo htmlspecialchars($row["lawyer_name"]); ?>
                </div>
              </td>
              <td><?php echo htmlspecialchars($row["case_title"]); ?></td>
              <td><span class="tag"><?php echo htmlspecialchars($row["case_type"]); ?></span></td>
              <td><?php echo htmlspecialchars($row["case_description"]); ?></td>
              <td><span class="tag"><?php echo htmlspecialchars($row["status"]); ?></span></td>
            </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="empty-state">No cases found.</td>
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