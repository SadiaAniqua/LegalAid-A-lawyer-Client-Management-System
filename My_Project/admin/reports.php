<?php

session_start();

include("../db.php");

if(!isset($_SESSION["id"]))
{
    header("Location: ../login.php");
    exit;
}

if($_SESSION["role"]!="admin")
{
    header("Location: ../dashboard.php");
    exit;
}

$message="";


/* ===== Icon Library ===== */

function icon($name)
{
    $icons=[

        'home'=>
        '<path d="M4 11.5 12 5l8 6.5"/>
         <path d="M6 10v9a1 1 0 0 0 1 1h4v-6h2v6h4a1 1 0 0 0 1-1-1v-9"/>',

        'users'=>
        '<circle cx="9" cy="8" r="3"/>
         <path d="M2.5 20c1-3.3 3.4-5 6.5-5s5.5 1.7 6.5 5"/>
         <circle cx="17" cy="9" r="2.4"/>
         <path d="M15.8 12.2c2.3.3 3.9 1.8 4.7 4.3"/>',

        'briefcase'=>
        '<rect x="3.5" y="8" width="17" height="11" rx="2"/>
         <path d="M8.5 8V6a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v2"/>
         <path d="M3.5 13h17"/>',

        'shield'=>
        '<path d="M12 3.5 19 6v6c0 4.4-3 7.8-7 8.5-4-.7-7-4.1-7-8.5V6z"/>
         <path d="m9 12 2 2 4-4.2"/>',

        'calendar'=>
        '<rect x="4" y="5.5" width="16" height="14.5" rx="2"/>
         <path d="M4 10h16M8 3.5v3M16 3.5v3"/>',

        'folder'=>
        '<path d="M3.5 7.5a1.5 1.5 0 0 1 1.5-1.5h4l2 2.2h8a1.5 1.5 0 0 1 1.5 1.5V18a1.5 1.5 0 0 1-1.5 1.5H5A1.5 1.5 0 0 1 3.5 18z"/>',

        'chart'=>
        '<path d="M4 20V10M11 20V4M18 20v-7"/>
         <path d="M2.5 20.5h19"/>',

        'logout'=>
        '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
         <path d="M16 17l5-5-5-5"/>
         <path d="M21 12H9"/>'
    ];

    $body=$icons[$name] ?? '';

    return '<svg viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.7"
        stroke-linecap="round"
        stroke-linejoin="round">'.$body.'</svg>';
}


/* ===== Ban User ===== */

if(isset($_GET["ban"]))
{
    $user_id=$_GET["ban"];

    $sql="UPDATE users

    SET status='banned'

    WHERE user_id='$user_id'";

    if(mysqli_query($conn,$sql))
    {
        $message="User Banned Successfully";
    }
}


/* ===== Dismiss Report ===== */

if(isset($_GET["dismiss"]))
{
    $report_id=$_GET["dismiss"];

    $sql="UPDATE reports

    SET status='dismissed'

    WHERE report_id='$report_id'";

    if(mysqli_query($conn,$sql))
    {
        $message="Report Dismissed Successfully";
    }
}


/* ===== Mark Report as Reviewed ===== */

if(isset($_GET["review"]))
{
    $report_id=$_GET["review"];

    $sql="UPDATE reports

    SET status='reviewed'

    WHERE report_id='$report_id'";

    if(mysqli_query($conn,$sql))
    {
        $message="Report Marked as Reviewed";
    }
}

?>


<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Reports & Complaints — LegalAid</title>

<link rel="icon" href="../logo/favicon-32.png">

<link rel="apple-touch-icon" href="../logo/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.googleapis.com">

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<link rel="stylesheet" href="reports.css">

</head>


<body>


<div class="app">


    <!-- ===== Sidebar ===== -->

    <nav class="sidebar">


        <a class="sidebar-logo" href="../dashboard.php">

            <img src="../logo/logo-icon.svg" alt="LegalAid">

        </a>


        <ul class="sidebar-nav">


            <li title="Dashboard">

                <a href="../dashboard.php">

                    <?php echo icon('home'); ?>

                </a>

            </li>


            <li title="View Clients">

                <a href="clients.php">

                    <?php echo icon('users'); ?>

                </a>

            </li>


            <li title="View Lawyers">

                <a href="lawyers.php">

                    <?php echo icon('briefcase'); ?>

                </a>

            </li>


            <li title="Lawyer Verification">

                <a href="verification.php">

                    <?php echo icon('shield'); ?>

                </a>

            </li>


            <li title="View Appointments">

                <a href="appointments.php">

                    <?php echo icon('calendar'); ?>

                </a>

            </li>


            <li title="View Cases">

                <a href="cases.php">

                    <?php echo icon('folder'); ?>

                </a>

            </li>


            <li class="active" title="View Reports">

                <a href="reports.php">

                    <?php echo icon('chart'); ?>

                </a>

            </li>


        </ul>


        <div class="sidebar-bottom">

            <a href="../logout.php" title="Logout">

                <?php echo icon('logout'); ?>

            </a>

        </div>


    </nav>



    <!-- ===== Main ===== -->

    <main class="main">


        <!-- Top Bar -->

        <header class="topbar">


            <div>

                <h1>LegalAid</h1>

                <p class="subtitle">

                    Admin Dashboard

                </p>

            </div>


            <div class="account">


                <span class="account-name">

                    <?php echo htmlspecialchars($_SESSION["username"]); ?>

                </span>


                <span class="account-role">

                    Admin

                </span>


                <div class="avatar">

                    <?php

                    echo strtoupper(
                        substr($_SESSION["username"],0,1)
                    );

                    ?>

                </div>


            </div>


        </header>



        <!-- ===== Page Header ===== -->

        <section class="page-header">


            <h2>Reports & Complaints</h2>


            <p>

                Review complaints and manage reported users on the LegalAid platform.

            </p>


        </section>



        <!-- ===== Message ===== -->

<?php

if($message!="")
{

?>

        <div class="message">

            <?php echo htmlspecialchars($message); ?>

        </div>

<?php

}

?>



        <!-- ===== Reports Table ===== -->

        <section class="table-card">


            <table class="reports-table">


                <thead>

                    <tr>

                        <th>Report ID</th>

                        <th>Reported By</th>

                        <th>Reported User</th>

                        <th>Appointment ID</th>

                        <th>Reason</th>

                        <th>Description</th>

                        <th>Status</th>

                        <th>Date</th>

                        <th>Action</th>

                    </tr>

                </thead>


                <tbody>


<?php


$sql="SELECT reports.*,

reporter.name AS reporter_name,

reported.name AS reported_name

FROM reports

JOIN users AS reporter

ON reports.reported_by=reporter.user_id

JOIN users AS reported

ON reports.reported_user=reported.user_id

ORDER BY reports.report_id DESC";


$result=mysqli_query($conn,$sql);


if(mysqli_num_rows($result)>0)
{

    while($row=mysqli_fetch_assoc($result))
    {

?>


                    <tr>


                        <!-- Report ID -->

                        <td>

                            <span class="report-id">

                                #<?php echo $row["report_id"]; ?>

                            </span>

                        </td>


                        <!-- Reporter -->

                        <td>

                            <span class="person-name">

                                <?php

                                echo htmlspecialchars(
                                    $row["reporter_name"]
                                );

                                ?>

                            </span>

                        </td>


                        <!-- Reported User -->

                        <td>

                            <span class="person-name">

                                <?php

                                echo htmlspecialchars(
                                    $row["reported_name"]
                                );

                                ?>

                            </span>

                        </td>


                        <!-- Appointment ID -->

                        <td>

                            <span class="appointment-id">

                                #<?php echo $row["appointment_id"]; ?>

                            </span>

                        </td>


                        <!-- Reason -->

                        <td>

                            <span class="reason">

                                <?php

                                echo htmlspecialchars(
                                    $row["reason"]
                                );

                                ?>

                            </span>

                        </td>


                        <!-- Description -->

                        <td>

                            <span class="description">

                                <?php

                                echo htmlspecialchars(
                                    $row["description"]
                                );

                                ?>

                            </span>

                        </td>


                        <!-- Status -->

                        <td>

<?php

if($row["status"]=="pending")
{

?>

                            <span class="status pending">

                                Pending

                            </span>

<?php

}

elseif($row["status"]=="reviewed")
{

?>

                            <span class="status reviewed">

                                Reviewed

                            </span>

<?php

}

elseif($row["status"]=="dismissed")
{

?>

                            <span class="status dismissed">

                                Dismissed

                            </span>

<?php

}

else

{

?>

                            <span class="status">

                                <?php

                                echo htmlspecialchars(
                                    $row["status"]
                                );

                                ?>

                            </span>

<?php

}

?>

                        </td>


                        <!-- Date -->

                        <td>

                            <span class="date">

                                <?php

                                echo htmlspecialchars(
                                    $row["created_at"]
                                );

                                ?>

                            </span>

                        </td>


                        <!-- Action -->

                        <td>


<?php

if($row["status"]=="pending")
{

?>


                            <div class="actions">


                                <a

                                    class="action-ban"

                                    href="reports.php?ban=<?php echo $row["reported_user"]; ?>"

                                >

                                    Ban User

                                </a>


                                <a

                                    class="action-review"

                                    href="reports.php?review=<?php echo $row["report_id"]; ?>"

                                >

                                    Mark Reviewed

                                </a>


                                <a

                                    class="action-dismiss"

                                    href="reports.php?dismiss=<?php echo $row["report_id"]; ?>"

                                >

                                    Dismiss

                                </a>


                            </div>


<?php

}

else

{

?>


                            <span class="no-action">

                                No Action

                            </span>


<?php

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

                        <td colspan="9">


                            <div class="empty-state">


                                <div class="empty-icon">

                                    <?php echo icon('chart'); ?>

                                </div>


                                <h3>No Reports Found</h3>


                                <p>

                                    There are currently no reports or complaints.

                                </p>


                            </div>


                        </td>

                    </tr>


<?php

}

?>


                </tbody>


            </table>


        </section>



        <!-- Footer -->

        <footer class="footer">

            &copy;

            <?php echo date("Y"); ?>

            LegalAid. All rights reserved.

        </footer>


    </main>


</div>


</body>

</html>
