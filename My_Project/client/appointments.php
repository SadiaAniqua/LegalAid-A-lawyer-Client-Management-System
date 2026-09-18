<?php

session_start();

include("../db.php");


/* =========================================================
   ACCESS CONTROL
   ========================================================= */

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


/* =========================================================
   APPOINTMENT CONTROLLER
   ========================================================= */

include("../controllers/AppointmentController.php");


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
         <path d="M6 10v9a1 1 0 0 0 1 1h4v-6h2v6h4a1 1 0 0 0 1-1-1v-9"/>',

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

<title>My Appointments — LegalAid</title>


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
      href="appointments.css">

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


        <!-- =================================================
             NAVIGATION
             ================================================= -->

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

            <li class="active"
                title="My Appointments">

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


        <!-- =================================================
             LOGOUT
             ================================================= -->

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


                <h1>
                    LegalAid
                </h1>


                <p class="subtitle">
                    My Appointments
                </p>


            </div>



            <!-- ACCOUNT -->

            <div class="account">


                <span class="account-name">

                    <?php

                    echo htmlspecialchars(
                        $username
                    );

                    ?>

                </span>


                <span class="account-role">

                    Client

                </span>


                <div class="avatar">

                    <?php

                    echo strtoupper(
                        substr(
                            $username,
                            0,
                            1
                        )
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
                    My Appointments
                </h2>


                <p>
                    Review, cancel, or reschedule your bookings.
                </p>


            </div>


        </section>



        <!-- =================================================
             MESSAGE
             ================================================= -->

        <?php if(!empty($message)): ?>


        <div class="message message-success">

            <?php

            echo htmlspecialchars(
                $message
            );

            ?>

        </div>


        <?php endif; ?>



        <!-- =================================================
             APPOINTMENT RESULTS
             ================================================= -->

        <section class="results-card">


            <div class="table-wrapper">


                <table>


                    <thead>


                        <tr>


                            <th>
                                ID
                            </th>


                            <th>
                                Lawyer
                            </th>


                            <th>
                                Specialization
                            </th>


                            <th>
                                Date
                            </th>


                            <th>
                                Time
                            </th>


                            <th>
                                Reason
                            </th>


                            <th>
                                Status
                            </th>


                            <th>
                            </th>


                        </tr>


                    </thead>



                    <tbody>


                    <?php if(
                        isset($result) &&
                        $result !== false &&
                        mysqli_num_rows($result) > 0
                    ): ?>


                        <?php while(
                            $row =
                            mysqli_fetch_assoc($result)
                        ): ?>


                        <tr>


                            <!-- APPOINTMENT ID -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $row["appointment_id"]
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



                            <!-- SPECIALIZATION -->

                            <td>


                                <span class="tag">

                                    <?php

                                    echo htmlspecialchars(
                                        $row["specialization_name"]
                                    );

                                    ?>

                                </span>


                            </td>



                            <!-- DATE -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $row["appointment_date"]
                                );

                                ?>

                            </td>



                            <!-- TIME -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $row["appointment_time"]
                                );

                                ?>

                            </td>



                            <!-- REASON -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $row["reason"]
                                );

                                ?>

                            </td>



                            <!-- STATUS -->

                            <td>


                                <span class="tag">

                                    <?php

                                    echo htmlspecialchars(
                                        $row["status"]
                                    );

                                    ?>

                                </span>


                            </td>



                            <!-- ACTIONS -->

                            <td class="action-cell">


                                <div class="action-stack">


                                    <!-- VIEW DETAILS -->

                                    <a
                                        class="button button-ghost"
                                        href="appointmentDetails.php?id=<?php

                                        echo urlencode(
                                            $row["appointment_id"]
                                        );

                                        ?>"
                                    >

                                        View Details

                                    </a>



                                    <!-- VIEW LAWYER -->

                                    <a
                                        class="button button-ghost"
                                        href="lawyerDetails.php?id=<?php

                                        echo urlencode(
                                            $row["lawyer_id"]
                                        );

                                        ?>"
                                    >

                                        View Lawyer

                                    </a>



                                    <!-- CANCEL -->

                                    <?php if(
                                        $row["status"] == "pending" ||
                                        $row["status"] == "accepted"
                                    ): ?>


                                    <a
                                        class="button button-danger"
                                        href="?cancel=<?php

                                        echo urlencode(
                                            $row["appointment_id"]
                                        );

                                        ?>"
                                    >

                                        Cancel

                                    </a>


                                    <?php endif; ?>



                                    <!-- RESCHEDULE -->

                                    <?php if(
                                        $row["status"] == "accepted"
                                    ): ?>


                                    <a
                                        class="button button-small"
                                        href="reschedule.php?id=<?php

                                        echo urlencode(
                                            $row["appointment_id"]
                                        );

                                        ?>"
                                    >

                                        Reschedule

                                    </a>


                                    <?php endif; ?>


                                </div>


                            </td>


                        </tr>


                        <?php endwhile; ?>


                    <?php else: ?>


                        <!-- EMPTY STATE -->

                        <tr>


                            <td
                                colspan="8"
                                class="empty-state"
                            >

                                No appointments found.

                            </td>


                        </tr>


                    <?php endif; ?>


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