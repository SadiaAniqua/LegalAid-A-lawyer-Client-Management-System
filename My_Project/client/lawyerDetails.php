```php
<?php

session_start();

include("../db.php");

if(!isset($_SESSION["id"]))
{
    header("Location: ../login.php");
    exit;
}

if($_SESSION["role"] != "client")
{
    header("Location: ../dashboard.php");
    exit;
}

$client_id = $_SESSION["id"];
$username = $_SESSION["username"];

$lawyer_id = $_GET["id"];


/* =========================================================
   LAWYER DETAILS
   ========================================================= */

$sql = "SELECT lawyer_profiles.*,
users.name,
users.email,
specializations.name AS specialization_name

FROM lawyer_profiles

JOIN users
ON lawyer_profiles.user_id=users.user_id

JOIN specializations
ON lawyer_profiles.specialization_id=
specializations.specialization_id

WHERE lawyer_profiles.lawyer_id='$lawyer_id'";

$result = mysqli_query($conn,$sql);

$row = mysqli_fetch_assoc($result);


/* =========================================================
   ICON LIBRARY
   ========================================================= */

function icon($name)
{
    $icons = [

        'home' =>
        '<path d="M4 11.5 12 5l8 6.5"/>
         <path d="M6 10v9a1 1 0 0 0 1 1h4v-6h2v6h4a1 1 0 0 0 1-1v-9"/>',

        'user' =>
        '<circle cx="12" cy="8" r="3.5"/>
         <path d="M5 20c1.2-3.6 4-5.5 7-5.5s5.8 1.9 7 5.5"/>',

        'search' =>
        '<circle cx="10.5" cy="10.5" r="6"/>
         <path d="m20 20-4.8-4.8"/>',

        'calendar' =>
        '<rect x="4" y="5.5" width="16" height="14.5" rx="2"/>
         <path d="M4 10h16M8 3.5v3M16 3.5v3"/>',

        'briefcase' =>
        '<rect x="3.5" y="8" width="17" height="11" rx="2"/>
         <path d="M8.5 8V6a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v2"/>
         <path d="M3.5 13h17"/>',

        'chart' =>
        '<path d="M4 20V10M11 20V4M18 20v-7"/>
         <path d="M2.5 20.5h19"/>',

        'logout' =>
        '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
         <path d="M16 17l5-5-5-5"/>
         <path d="M21 12H9"/>',

        'back' =>
        '<path d="M19 12H5"/>
         <path d="m12 19-7-7 7-7"/>'
    ];

    $body = $icons[$name] ?? '';

    return '<svg viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.7"
        stroke-linecap="round"
        stroke-linejoin="round">' . $body . '</svg>';
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Lawyer Details — LegalAid</title>


<!-- =====================================================
     FAVICON
     ===================================================== -->

<link rel="icon"
      href="../logo/favicon-32.png">

<link rel="apple-touch-icon"
      href="../logo/apple-touch-icon.png">


<!-- =====================================================
     GOOGLE FONTS
     ===================================================== -->

<link rel="preconnect"
      href="https://fonts.googleapis.com">

<link rel="preconnect"
      href="https://fonts.gstatic.com"
      crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=Inter:wght@400;500;600&display=swap"
      rel="stylesheet">


<!-- =====================================================
     CSS
     ===================================================== -->

<link rel="stylesheet"
      href="lawyerDetails.css">

</head>


<body>


<div class="app">


    <!-- =================================================
         SIDEBAR
         ================================================= -->

    <nav class="sidebar">


        <!-- LOGO -->

        <a class="sidebar-logo"
           href="../dashboard.php">

            <img src="../logo/logo-icon.svg"
                 alt="LegalAid">

        </a>


        <!-- NAVIGATION -->

        <ul class="sidebar-nav">


            <!-- DASHBOARD -->

            <li title="Dashboard">

                <a href="../dashboard.php">

                    <?php echo icon('home'); ?>

                </a>

            </li>


            <!-- PROFILE -->

            <li title="Edit Profile">

                <a href="profile.php">

                    <?php echo icon('user'); ?>

                </a>

            </li>


            <!-- FIND LAWYER -->

            <li class="active"
                title="Find Lawyer">

                <a href="findLawyer.php">

                    <?php echo icon('search'); ?>

                </a>

            </li>


            <!-- APPOINTMENTS -->

            <li title="My Appointments">

                <a href="appointments.php">

                    <?php echo icon('calendar'); ?>

                </a>

            </li>


            <!-- CASES -->

            <li title="My Cases">

                <a href="cases.php">

                    <?php echo icon('briefcase'); ?>

                </a>

            </li>


            <!-- REPORTS -->

            <li title="Reports">

                <a href="reports.php">

                    <?php echo icon('chart'); ?>

                </a>

            </li>


        </ul>


        <!-- LOGOUT -->

        <div class="sidebar-bottom">

            <a href="../logout.php"
               title="Logout">

                <?php echo icon('logout'); ?>

            </a>

        </div>


    </nav>



    <!-- =================================================
         MAIN
         ================================================= -->

    <main class="main">


        <!-- =================================================
             TOPBAR
             ================================================= -->

        <header class="topbar">


            <div>

                <h1>
                    LegalAid
                </h1>

                <p class="subtitle">
                    Lawyer Profile
                </p>

            </div>


            <!-- ACCOUNT -->

            <div class="account">


                <div class="account-info">

                    <span class="account-name">

                        <?php

                        echo htmlspecialchars($username);

                        ?>

                    </span>

                    <span class="account-role">
                        Client
                    </span>

                </div>


                <div class="avatar">

                    <?php

                    echo strtoupper(
                        substr($username, 0, 1)
                    );

                    ?>

                </div>


            </div>


        </header>



        <!-- =================================================
             PAGE HEADING
             ================================================= -->

        <section class="page-heading">


            <div>

                <h2>
                    Lawyer Profile
                </h2>

                <p>
                    View information about this lawyer.
                </p>

            </div>


        </section>



        <!-- =================================================
             BACK LINK
             ================================================= -->

        <a class="back-link"
           href="appointments.php">

            <?php echo icon('back'); ?>

            Back to My Appointments

        </a>



        <!-- =================================================
             LAWYER PROFILE CARD
             ================================================= -->

        <section class="profile-card">


            <!-- PROFILE HEADER -->

            <div class="profile-header">


                <div class="lawyer-info">


                    <div class="lawyer-avatar">

                        <?php

                        echo strtoupper(
                            substr(
                                $row["name"],
                                0,
                                1
                            )
                        );

                        ?>

                    </div>


                    <div>

                        <div class="lawyer-name">

                            <?php

                            echo htmlspecialchars(
                                $row["name"]
                            );

                            ?>

                        </div>


                        <div class="lawyer-specialization">

                            <?php

                            echo htmlspecialchars(
                                $row["specialization_name"]
                            );

                            ?>

                        </div>

                    </div>


                </div>


                <span class="tag">

                    <?php

                    echo htmlspecialchars(
                        $row["specialization_name"]
                    );

                    ?>

                </span>


            </div>



            <!-- =================================================
                 LAWYER INFORMATION
                 ================================================= -->

            <div class="details-list">


                <!-- EMAIL -->

                <div class="detail-item">

                    <div class="detail-label">
                        Email
                    </div>

                    <div class="detail-value">

                        <?php

                        echo htmlspecialchars(
                            $row["email"]
                        );

                        ?>

                    </div>

                </div>


                <!-- EXPERIENCE -->

                <div class="detail-item">

                    <div class="detail-label">
                        Experience
                    </div>

                    <div class="detail-value">

                        <?php

                        echo htmlspecialchars(
                            $row["experience"]
                        );

                        ?>

                        years

                    </div>

                </div>


                <!-- CHAMBER -->

                <div class="detail-item">

                    <div class="detail-label">
                        Chamber
                    </div>

                    <div class="detail-value">

                        <?php

                        echo htmlspecialchars(
                            $row["chamber_name"]
                        );

                        ?>

                    </div>

                </div>


                <!-- CONSULTATION FEE -->

                <div class="detail-item">

                    <div class="detail-label">
                        Consultation Fee
                    </div>

                    <div class="detail-value">

                        <?php

                        echo htmlspecialchars(
                            $row["consultation_fee"]
                        );

                        ?>

                    </div>

                </div>


                <!-- ADDRESS -->

                <div class="detail-item full-width">

                    <div class="detail-label">
                        Address
                    </div>

                    <div class="detail-value">

                        <?php

                        echo htmlspecialchars(
                            $row["address"]
                        );

                        ?>

                    </div>

                </div>


            </div>



            <!-- =================================================
                 ABOUT
                 ================================================= -->

            <div class="about-section">


                <div class="about-label">
                    About
                </div>


                <div class="about-text">

                    <?php

                    echo nl2br(
                        htmlspecialchars(
                            $row["bio"]
                        )
                    );

                    ?>

                </div>


            </div>



            <!-- =================================================
                 ACTIONS
                 ================================================= -->

            <div class="actions">


                <a class="button button-ghost"
                   href="appointments.php">

                    Back to Appointments

                </a>


                <a class="button button-small"
                   href="findLawyer.php">

                    Find Another Lawyer

                </a>


            </div>


        </section>



        <!-- =================================================
             FOOTER
             ================================================= -->

        <footer class="footer">

            &copy;

            <?php

            echo date("Y");

            ?>

            LegalAid. All rights reserved.

        </footer>


    </main>


</div>


</body>

</html>
```
