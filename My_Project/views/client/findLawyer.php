<?php

session_start();

if(!isset($_SESSION["id"]))
{
    header("Location: ../../login.php");
    exit();
}

include("../../controllers/LawyerController.php");

$username = $_SESSION["username"];
$activePage = 'findLawyer';

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Find a Lawyer — LegalAid</title>
<link rel="icon" href="../../logo/favicon-32.png">
<link rel="apple-touch-icon" href="../../logo/apple-touch-icon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="findLawyer.css">
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
        <h1>Find a Lawyer</h1>
        <p class="subtitle">Search verified lawyers by name or specialization.</p>
      </div>
      <div class="account">
        <span class="account-name"><?php echo htmlspecialchars($username); ?></span>
        <div class="avatar"><?php echo strtoupper(substr($username, 0, 1)); ?></div>
      </div>
    </header>

    <div class="search-bar">
      <div class="search-field">
        <span class="search-icon"><?php echo icon('search'); ?></span>
        <input type="text" id="search" placeholder="Search lawyer by name">
      </div>

      <select id="specialization_id">
        <option value="">All specializations</option>
        <?php while ($specialization = mysqli_fetch_assoc($specializations)): ?>
        <option value="<?php echo htmlspecialchars($specialization["specialization_id"]); ?>">
          <?php echo htmlspecialchars($specialization["name"]); ?>
        </option>
        <?php endwhile; ?>
      </select>
    </div>

    <section class="results-card">
      <table>
        <thead>
          <tr>
            <th>Lawyer</th>
            <th>Specialization</th>
            <th>Experience</th>
            <th>Chamber</th>
            <th>Consultation Fee</th>
            <th></th>
          </tr>
        </thead>
        <tbody id="lawyerResult">
          <?php
          /* Initial lawyer list */
          if (mysqli_num_rows($result) > 0):
              while ($row = mysqli_fetch_assoc($result)):
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
              <a class="button button-small" href="../../client/bookAppointment.php?lawyer_id=<?php echo urlencode($row["lawyer_id"]); ?>">Book Appointment</a>
            </td>
          </tr>
          <?php
              endwhile;
          else:
          ?>
          <tr>
            <td colspan="6" class="empty-state">No lawyers found.</td>
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

<script>

function searchLawyers()
{

    var search =
        document.getElementById("search").value;

    var specialization =
        document.getElementById(
            "specialization_id"
        ).value;


    var xhr =
        new XMLHttpRequest();


    xhr.onreadystatechange = function()
    {

        if(
            xhr.readyState == 4 &&
            xhr.status == 200
        )
        {

            document.getElementById(
                "lawyerResult"
            ).innerHTML = xhr.responseText;

        }

    };


    xhr.open(
        "POST",
        "../../ajax/lawyerSearch.php",
        true
    );


    xhr.setRequestHeader(
        "Content-type",
        "application/x-www-form-urlencoded"
    );


    xhr.send(
        "search=" +
        encodeURIComponent(search) +
        "&specialization_id=" +
        encodeURIComponent(specialization)
    );

}


/* Search while typing */

document.getElementById("search")
.addEventListener(
    "keyup",
    searchLawyers
);


/* Search when specialization changes */

document.getElementById("specialization_id")
.addEventListener(
    "change",
    searchLawyers
);

</script>

</body>
</html>