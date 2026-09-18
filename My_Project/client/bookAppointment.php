<?php

session_start();

include("../db.php");

if(!isset($_SESSION["id"]))
{
    header("Location: ../login.php");
    exit();
}

if($_SESSION["role"] != "client")
{
    header("Location: ../dashboard.php");
    exit();
}

$client_id = $_SESSION["id"];
$username = $_SESSION["username"];

$message = "";
$message_type = "";


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

        'back' =>
        '<path d="M19 12H5"/>
         <path d="m12 19-7-7 7-7"/>',

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


/* =========================================================
   GET LAWYER ID
   ========================================================= */

if(isset($_GET["lawyer_id"]))
{
    $lawyer_id = mysqli_real_escape_string(
        $conn,
        $_GET["lawyer_id"]
    );
}
else
{
    header("Location: findLawyer.php");
    exit();
}


/* =========================================================
   GET LAWYER INFORMATION
   ========================================================= */

$sql = "SELECT
            lawyer_profiles.*,
            users.name,
            specializations.name AS specialization_name

        FROM lawyer_profiles

        JOIN users
        ON lawyer_profiles.user_id = users.user_id

        JOIN specializations
        ON lawyer_profiles.specialization_id =
           specializations.specialization_id

        WHERE lawyer_profiles.lawyer_id='$lawyer_id'";

$result = mysqli_query($conn, $sql);

if(!$result || mysqli_num_rows($result) == 0)
{
    header("Location: findLawyer.php");
    exit();
}

$row = mysqli_fetch_assoc($result);


/* =========================================================
   BOOK APPOINTMENT
   ========================================================= */

if(isset($_POST["book"]))
{
    $appointment_date = mysqli_real_escape_string(
        $conn,
        $_POST["appointment_date"]
    );

    $appointment_time = mysqli_real_escape_string(
        $conn,
        $_POST["appointment_time"]
    );

    $reason = mysqli_real_escape_string(
        $conn,
        $_POST["reason"]
    );


    $sql2 = "INSERT INTO appointments
            (
                client_id,
                lawyer_id,
                appointment_date,
                appointment_time,
                reason,
                status
            )

            VALUES
            (
                '$client_id',
                '$lawyer_id',
                '$appointment_date',
                '$appointment_time',
                '$reason',
                'pending'
            )";


    if(mysqli_query($conn, $sql2))
    {
        $message = "Appointment Booked Successfully";
        $message_type = "success";
    }
    else
    {
        $message = "Appointment Booking Failed";
        $message_type = "error";
    }
}

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Book Appointment — LegalAid</title>

    <link
        rel="icon"
        href="../logo/favicon-32.png"
    >

    <link
        rel="apple-touch-icon"
        href="../logo/apple-touch-icon.png"
    >

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="bookAppointment.css"
    >

</head>


<body>


<div class="app">


    <!-- =====================================================
         SIDEBAR
         ===================================================== -->

    <nav class="sidebar">


        <!-- LOGO -->

        <a
            class="sidebar-logo"
            href="../dashboard.php"
        >

            <img
                src="../logo/logo-icon.svg"
                alt="LegalAid"
            >

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

            <li
                class="active"
                title="Find Lawyer"
            >

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

            <a
                href="../logout.php"
                title="Logout"
            >

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


            <div class="topbar-left">


                <a
                    class="back-link"
                    href="findLawyer.php"
                >

                    <?php echo icon('back'); ?>

                    Back to Find Lawyer

                </a>


                <h1>
                    Book Appointment
                </h1>


                <p class="subtitle">
                    Schedule a consultation with your selected lawyer.
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
             MESSAGE
             ================================================= -->

        <?php if($message != ""): ?>

            <div class="message <?php echo $message_type; ?>">

                <?php
                echo htmlspecialchars($message);
                ?>

            </div>

        <?php endif; ?>



        <!-- =================================================
             LAWYER INFORMATION
             ================================================= -->

        <section class="profile-card">


            <div class="card-header">

                <div>

                    <h2>
                        Lawyer Information
                    </h2>

                    <p>
                        Review the lawyer's professional information before booking.
                    </p>

                </div>

            </div>


            <div class="lawyer-profile">


                <div class="lawyer-avatar">

                    <?php

                    echo strtoupper(
                        substr($row["name"], 0, 1)
                    );

                    ?>

                </div>


                <div class="lawyer-details">


                    <h3>

                        <?php
                        echo htmlspecialchars(
                            $row["name"]
                        );
                        ?>

                    </h3>


                    <span class="specialization-tag">

                        <?php
                        echo htmlspecialchars(
                            $row["specialization_name"]
                        );
                        ?>

                    </span>


                    <div class="lawyer-info-grid">


                        <div class="lawyer-info-item">

                            <span class="info-label">
                                Experience
                            </span>

                            <span class="info-value">

                                <?php
                                echo htmlspecialchars(
                                    $row["experience"]
                                );
                                ?>

                                years

                            </span>

                        </div>


                        <div class="lawyer-info-item">

                            <span class="info-label">
                                Consultation Fee
                            </span>

                            <span class="info-value">

                                <?php
                                echo htmlspecialchars(
                                    $row["consultation_fee"]
                                );
                                ?>

                                BDT

                            </span>

                        </div>


                        <div class="lawyer-info-item">

                            <span class="info-label">
                                Chamber
                            </span>

                            <span class="info-value">

                                <?php
                                echo htmlspecialchars(
                                    $row["chamber_name"]
                                );
                                ?>

                            </span>

                        </div>


                        <div class="lawyer-info-item">

                            <span class="info-label">
                                Address
                            </span>

                            <span class="info-value">

                                <?php
                                echo htmlspecialchars(
                                    $row["address"]
                                );
                                ?>

                            </span>

                        </div>


                    </div>


                </div>


            </div>


        </section>



        <!-- =================================================
             BOOK APPOINTMENT
             ================================================= -->

        <section class="profile-card">


            <div class="card-header">

                <div>

                    <h2>
                        Appointment Details
                    </h2>

                    <p>
                        Choose a convenient date and time and tell the lawyer why you need a consultation.
                    </p>

                </div>

            </div>



            <form
                method="post"
                class="appointment-form"
            >


                <div class="form-grid">


                    <!-- DATE -->

                    <div class="form-group">

                        <label for="appointment_date">
                            Appointment Date
                        </label>

                        <input
                            type="date"
                            id="appointment_date"
                            name="appointment_date"
                            required
                        >

                    </div>


                    <!-- TIME -->

                    <div class="form-group">

                        <label for="appointment_time">
                            Appointment Time
                        </label>

                        <input
                            type="time"
                            id="appointment_time"
                            name="appointment_time"
                            required
                        >

                    </div>


                    <!-- REASON -->

                    <div class="form-group full-width">

                        <label for="reason">
                            Reason for Consultation
                        </label>

                        <textarea
                            id="reason"
                            name="reason"
                            rows="6"
                            placeholder="Briefly describe the reason for your consultation..."
                            required
                        ></textarea>

                    </div>


                </div>


                <!-- FORM ACTIONS -->

                <div class="form-actions">


                    <a
                        href="findLawyer.php"
                        class="button button-secondary"
                    >
                        Cancel
                    </a>


                    <button
                        type="submit"
                        name="book"
                        class="button button-primary"
                    >
                        Book Appointment
                    </button>


                </div>


            </form>


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