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

$username=$_SESSION["username"];


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

?>


<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>View Lawyers — LegalAid</title>

<link rel="icon" href="../logo/favicon-32.png">

<link rel="apple-touch-icon" href="../logo/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.googleapis.com">

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<link rel="stylesheet" href="lawyers.css">

</head>


<body>


<div class="app">


    <!-- Sidebar -->

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


            <li class="active" title="View Lawyers">

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


            <li title="View Reports">

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



    <!-- Main -->

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

                    <?php echo htmlspecialchars($username); ?>

                </span>


                <span class="account-role">

                    Admin

                </span>


                <div class="avatar">

                    <?php echo strtoupper(substr($username,0,1)); ?>

                </div>


            </div>


        </header>



        <!-- Page Header -->

        <section class="page-header">


            <h2>Registered Lawyers</h2>


            <p>

                View all registered lawyers on the LegalAid platform.

            </p>


        </section>



        <!-- Lawyers Table -->

        <section class="table-card">


            <table class="lawyers-table">


                <thead>

                    <tr>

                        <th>Lawyer ID</th>

                        <th>Name</th>

                        <th>Email</th>

                        <th>Phone</th>

                        <th>Specialization</th>

                        <th>Experience</th>

                        <th>Chamber</th>

                        <th>Address</th>

                        <th>Status</th>

                    </tr>

                </thead>


                <tbody>


<?php


$sql="SELECT

lawyer_profiles.*,

users.name,

users.email,

users.phone,

specializations.name AS specialization_name

FROM lawyer_profiles

JOIN users

ON lawyer_profiles.user_id=users.user_id

JOIN specializations

ON lawyer_profiles.specialization_id=
specializations.specialization_id

ORDER BY lawyer_profiles.lawyer_id DESC";


$result=mysqli_query($conn,$sql);


if(mysqli_num_rows($result)>0)
{

    while($row=mysqli_fetch_assoc($result))
    {

?>


                    <tr>


                        <td>

                            <span class="lawyer-id">

                                #<?php echo $row["lawyer_id"]; ?>

                            </span>

                        </td>


                        <td>

                            <span class="lawyer-name">

                                <?php echo htmlspecialchars($row["name"]); ?>

                            </span>

                        </td>


                        <td>

                            <span class="email">

                                <?php echo htmlspecialchars($row["email"]); ?>

                            </span>

                        </td>


                        <td>

                            <span class="phone">

                                <?php echo htmlspecialchars($row["phone"]); ?>

                            </span>

                        </td>


                        <td>

                            <span class="specialization">

                                <?php echo htmlspecialchars($row["specialization_name"]); ?>

                            </span>

                        </td>


                        <td>

                            <span class="experience">

                                <?php echo htmlspecialchars($row["experience"]); ?>

                                years

                            </span>

                        </td>


                        <td>

                            <span class="chamber">

                                <?php echo htmlspecialchars($row["chamber_name"]); ?>

                            </span>

                        </td>


                        <td>

                            <span class="address">

                                <?php echo htmlspecialchars($row["address"]); ?>

                            </span>

                        </td>


                        <td>

                            <span class="status">

                                <?php echo htmlspecialchars($row["verification_status"]); ?>

                            </span>

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

                                    <?php echo icon('briefcase'); ?>

                                </div>


                                <h3>No Lawyers Found</h3>


                                <p>

                                    There are currently no registered lawyers.

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