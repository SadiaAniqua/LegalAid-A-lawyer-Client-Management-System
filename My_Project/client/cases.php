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

include("../controllers/ClientCaseController.php");

$client_id = $_SESSION["id"];
$username = $_SESSION["username"];


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

        'users' =>
        '<circle cx="9" cy="8" r="3"/>
         <path d="M2.5 20c1-3.3 3.4-5 6.5-5s5.5 1.7 6.5 5"/>
         <circle cx="17" cy="9" r="2.4"/>
         <path d="M15.8 12.2c2.3.3 3.9 1.8 4.7 4.3"/>',

        'shield' =>
        '<path d="M12 3.5 19 6v6c0 4.4-3 7.8-7 8.5-4-.7-7-4.1-7-8.5V6z"/>
         <path d="m9 12 2 2 4-4.2"/>',

        'folder' =>
        '<path d="M3.5 7.5a1.5 1.5 0 0 1 1.5-1.5h4l2 2.2h8a1.5 1.5 0 0 1 1.5 1.5V18a1.5 1.5 0 0 1-1.5 1.5H5A1.5 1.5 0 0 1 3.5 18z"/>',

        'flag' =>
        '<path d="M5 21V4"/>
         <path d="M5 4c3-2 5 2 8 0s5 2 6 0v9c-3 2-5-2-8 0s-5-2-6 0"/>',

        'logout' =>
        '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
         <path d="M16 17l5-5-5-5"/>
         <path d="M21 12H9"/>'
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

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Cases — LegalAid</title>

<link rel="icon" href="../logo/favicon-32.png">

<link rel="apple-touch-icon" href="../logo/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.googleapis.com">

<link rel="preconnect"
      href="https://fonts.gstatic.com"
      crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=Inter:wght@400;500;600&display=swap"
      rel="stylesheet">

<link rel="stylesheet" href="cases.css">

</head>


<body>


<div class="app">


    <!-- =====================================================
         SIDEBAR
         ===================================================== -->

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

            <li title="Find Lawyer">

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

            <li class="active"
                title="My Cases">

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



    <!-- =====================================================
         MAIN
         ===================================================== -->

    <main class="main">


        <!-- =================================================
             TOPBAR
             ================================================= -->

        <header class="topbar">


            <div>

                <h1>LegalAid</h1>

                <p class="subtitle">
                    My Cases
                </p>

            </div>



            <!-- ACCOUNT -->

            <div class="account">


                <span class="account-name">

                    <?php
                    echo htmlspecialchars($username);
                    ?>

                </span>


                <span class="account-role">

                    Client

                </span>


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
                    My Cases
                </h2>

                <p>
                    Track the status of cases you're involved in.
                </p>

            </div>


        </section>



        <!-- =================================================
             CASE RESULTS
             ================================================= -->

        <section class="results-card">


            <div class="table-wrapper">


                <table>


                    <thead>

                        <tr>

                            <th>
                                Case ID
                            </th>

                            <th>
                                Lawyer
                            </th>

                            <th>
                                Case Title
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Description
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>



                    <tbody>


                    <?php

                    if(
                        isset($result) &&
                        mysqli_num_rows($result) > 0
                    ):

                        while(
                            $row =
                            mysqli_fetch_assoc($result)
                        ):

                    ?>


                    <tr>


                        <!-- CASE ID -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row["case_id"]
                            );

                            ?>

                        </td>



                        <!-- LAWYER -->

                        <td>


                            <div class="person-cell">


                                <span class="person-avatar">

                                    <?php

                                    echo strtoupper(
                                        substr(
                                            $row["lawyer_name"],
                                            0,
                                            1
                                        )
                                    );

                                    ?>

                                </span>


                                <span>

                                    <?php

                                    echo htmlspecialchars(
                                        $row["lawyer_name"]
                                    );

                                    ?>

                                </span>


                            </div>


                        </td>



                        <!-- CASE TITLE -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row["case_title"]
                            );

                            ?>

                        </td>



                        <!-- CASE TYPE -->

                        <td>


                            <span class="tag">

                                <?php

                                echo htmlspecialchars(
                                    $row["case_type"]
                                );

                                ?>

                            </span>


                        </td>



                        <!-- DESCRIPTION -->

                        <td class="description-cell">

                            <?php

                            echo htmlspecialchars(
                                $row["case_description"]
                            );

                            ?>

                        </td>



                        <!-- STATUS -->

                        <td>


                            <span class="status-badge
                            <?php

                            echo strtolower(
                                str_replace(
                                    ' ',
                                    '-',
                                    $row["status"]
                                )
                            );

                            ?>">

                                <?php

                                echo htmlspecialchars(
                                    $row["status"]
                                );

                                ?>

                            </span>


                        </td>


                    </tr>


                    <?php

                        endwhile;

                    else:

                    ?>


                    <tr>


                        <td
                            colspan="6"
                            class="empty-state"
                        >

                            <div class="empty-icon">

                                <?php
                                echo icon('briefcase');
                                ?>

                            </div>

                            <strong>
                                No Cases Found
                            </strong>

                            <span>
                                Your cases will appear here.
                            </span>

                        </td>


                    </tr>


                    <?php

                    endif;

                    ?>


                    </tbody>


                </table>


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