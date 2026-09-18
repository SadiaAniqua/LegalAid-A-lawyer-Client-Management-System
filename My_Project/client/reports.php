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


/* =========================================================
   SUBMIT REPORT
   ========================================================= */

if(isset($_POST["report"]))
{
    $reported_user = mysqli_real_escape_string(
        $conn,
        $_POST["reported_user"] ?? ""
    );

    $appointment_id = mysqli_real_escape_string(
        $conn,
        $_POST["appointment_id"] ?? ""
    );

    $reason = mysqli_real_escape_string(
        $conn,
        $_POST["reason"] ?? ""
    );

    $description = mysqli_real_escape_string(
        $conn,
        $_POST["description"] ?? ""
    );


    if(
        empty($reported_user) ||
        empty($appointment_id) ||
        empty($description)
    )
    {
        $message = "Please complete all required fields.";
        $message_type = "error";
    }
    else
    {
        $sql = "INSERT INTO reports
                (
                    reported_by,
                    reported_user,
                    appointment_id,
                    reason,
                    description,
                    status
                )
                VALUES
                (
                    '$client_id',
                    '$reported_user',
                    '$appointment_id',
                    '$reason',
                    '$description',
                    'pending'
                )";


        if(mysqli_query($conn, $sql))
        {
            $message = "Report submitted successfully.";
            $message_type = "success";
        }
        else
        {
            $message = "Report submission failed.";
            $message_type = "error";
        }
    }
}


/* =========================================================
   APPOINTMENT SUMMARY
   ========================================================= */

$sql = "SELECT COUNT(*) AS total
        FROM appointments
        WHERE client_id='$client_id'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$total_appointments = $row["total"];


$sql = "SELECT COUNT(*) AS total
        FROM appointments
        WHERE client_id='$client_id'
        AND status='pending'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$pending_appointments = $row["total"];


$sql = "SELECT COUNT(*) AS total
        FROM appointments
        WHERE client_id='$client_id'
        AND status='accepted'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$accepted_appointments = $row["total"];


$sql = "SELECT COUNT(*) AS total
        FROM appointments
        WHERE client_id='$client_id'
        AND status='completed'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$completed_appointments = $row["total"];


$sql = "SELECT COUNT(*) AS total
        FROM appointments
        WHERE client_id='$client_id'
        AND status='cancelled'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$cancelled_appointments = $row["total"];


/* =========================================================
   CASE SUMMARY
   ========================================================= */

$sql = "SELECT COUNT(*) AS total
        FROM cases
        WHERE client_id='$client_id'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$total_cases = $row["total"];


$sql = "SELECT COUNT(*) AS total
        FROM cases
        WHERE client_id='$client_id'
        AND status='Open'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$open_cases = $row["total"];


$sql = "SELECT COUNT(*) AS total
        FROM cases
        WHERE client_id='$client_id'
        AND status='In Progress'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$in_progress_cases = $row["total"];


$sql = "SELECT COUNT(*) AS total
        FROM cases
        WHERE client_id='$client_id'
        AND status='Completed'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$completed_cases = $row["total"];


$sql = "SELECT COUNT(*) AS total
        FROM cases
        WHERE client_id='$client_id'
        AND status='Closed'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$closed_cases = $row["total"];

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reports — LegalAid</title>


    <link rel="icon" href="../logo/favicon-32.png">

    <link rel="apple-touch-icon" href="../logo/apple-touch-icon.png">


    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet"
    >


    <link rel="stylesheet" href="reports.css">

</head>


<body>


<div class="app">


    <!-- =====================================================
         SIDEBAR
         ===================================================== -->

    <nav class="sidebar">


        <a
            class="sidebar-logo"
            href="../dashboard.php"
        >

            <img
                src="../logo/logo-icon.svg"
                alt="LegalAid"
            >

        </a>


        <ul class="sidebar-nav">


            <!-- DASHBOARD -->

            <li title="Dashboard">

                <a href="../dashboard.php">

                    <?php echo icon('home'); ?>

                </a>

            </li>


            <!-- EDIT PROFILE -->

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

            <li title="My Cases">

                <a href="cases.php">

                    <?php echo icon('briefcase'); ?>

                </a>

            </li>


            <!-- REPORTS -->

            <li
                class="active"
                title="Reports"
            >

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


        <!-- TOPBAR -->

        <header class="topbar">


            <div>

                <h1>LegalAid</h1>

                <p class="subtitle">
                    Client Reports
                </p>

            </div>


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



        <!-- =====================================================
             PAGE HEADING
             ===================================================== -->

        <section class="page-heading">


            <div>

                <h2>My Reports</h2>

                <p>
                    Review your activity and report a user when necessary.
                </p>

            </div>


        </section>



        <!-- =====================================================
             MESSAGE
             ===================================================== -->

        <?php if(!empty($message)): ?>

            <div
                class="message <?php echo $message_type; ?>"
            >

                <?php
                echo htmlspecialchars($message);
                ?>

            </div>

        <?php endif; ?>



        <!-- =====================================================
             APPOINTMENT SUMMARY
             ===================================================== -->

        <section class="section">


            <div class="section-heading">


                <div class="section-icon">

                    <?php echo icon('calendar'); ?>

                </div>


                <div>

                    <h3>Appointment Summary</h3>

                    <p>
                        Overview of your appointment activity.
                    </p>

                </div>


            </div>


            <div class="stats-grid">


                <!-- TOTAL -->

                <div class="stat-card">

                    <span class="stat-label">
                        Total Appointments
                    </span>

                    <strong>
                        <?php
                        echo $total_appointments;
                        ?>
                    </strong>

                </div>


                <!-- PENDING -->

                <div class="stat-card">

                    <span class="stat-label">
                        Pending
                    </span>

                    <strong>
                        <?php
                        echo $pending_appointments;
                        ?>
                    </strong>

                </div>


                <!-- ACCEPTED -->

                <div class="stat-card">

                    <span class="stat-label">
                        Accepted
                    </span>

                    <strong>
                        <?php
                        echo $accepted_appointments;
                        ?>
                    </strong>

                </div>


                <!-- COMPLETED -->

                <div class="stat-card">

                    <span class="stat-label">
                        Completed
                    </span>

                    <strong>
                        <?php
                        echo $completed_appointments;
                        ?>
                    </strong>

                </div>


                <!-- CANCELLED -->

                <div class="stat-card">

                    <span class="stat-label">
                        Cancelled
                    </span>

                    <strong>
                        <?php
                        echo $cancelled_appointments;
                        ?>
                    </strong>

                </div>


            </div>


        </section>



        <!-- =====================================================
             CASE SUMMARY
             ===================================================== -->

        <section class="section">


            <div class="section-heading">


                <div class="section-icon">

                    <?php echo icon('briefcase'); ?>

                </div>


                <div>

                    <h3>Case Summary</h3>

                    <p>
                        Overview of your current and previous cases.
                    </p>

                </div>


            </div>


            <div class="stats-grid">


                <!-- TOTAL CASES -->

                <div class="stat-card">

                    <span class="stat-label">
                        Total Cases
                    </span>

                    <strong>
                        <?php
                        echo $total_cases;
                        ?>
                    </strong>

                </div>


                <!-- OPEN -->

                <div class="stat-card">

                    <span class="stat-label">
                        Open
                    </span>

                    <strong>
                        <?php
                        echo $open_cases;
                        ?>
                    </strong>

                </div>


                <!-- IN PROGRESS -->

                <div class="stat-card">

                    <span class="stat-label">
                        In Progress
                    </span>

                    <strong>
                        <?php
                        echo $in_progress_cases;
                        ?>
                    </strong>

                </div>


                <!-- COMPLETED -->

                <div class="stat-card">

                    <span class="stat-label">
                        Completed
                    </span>

                    <strong>
                        <?php
                        echo $completed_cases;
                        ?>
                    </strong>

                </div>


                <!-- CLOSED -->

                <div class="stat-card">

                    <span class="stat-label">
                        Closed
                    </span>

                    <strong>
                        <?php
                        echo $closed_cases;
                        ?>
                    </strong>

                </div>


            </div>


        </section>



        <!-- =====================================================
             REPORT FORM
             ===================================================== -->

        <section class="section">


            <div class="section-heading">


                <div class="section-icon">

                    <?php echo icon('flag'); ?>

                </div>


                <div>

                    <h3>Report a User</h3>

                    <p>
                        Submit a report if you have experienced
                        inappropriate behaviour or misconduct.
                    </p>

                </div>


            </div>



            <div class="form-card">


                <form method="post">


                    <div class="form-grid">


                        <!-- =================================================
                             REPORTED USER
                             ================================================= -->

                        <div class="form-group">


                            <label for="reported_user">
                                Reported User
                            </label>


                            <select
                                name="reported_user"
                                id="reported_user"
                                required
                            >

                                <option value="">
                                    Select User
                                </option>


                                <?php

                                $sql = "SELECT
                                            user_id,
                                            name,
                                            role
                                        FROM users
                                        WHERE user_id!='$client_id'
                                        AND status='active'
                                        ORDER BY name";

                                $result = mysqli_query(
                                    $conn,
                                    $sql
                                );


                                while(
                                    $row = mysqli_fetch_assoc($result)
                                )
                                {

                                ?>

                                    <option
                                        value="<?php
                                        echo htmlspecialchars(
                                            $row["user_id"]
                                        );
                                        ?>"
                                    >

                                        <?php
                                        echo htmlspecialchars(
                                            $row["name"]
                                        );
                                        ?>

                                        (
                                        <?php
                                        echo htmlspecialchars(
                                            $row["role"]
                                        );
                                        ?>
                                        )

                                    </option>

                                <?php

                                }

                                ?>

                            </select>


                        </div>



                        <!-- =================================================
                             APPOINTMENT
                             ================================================= -->

                        <div class="form-group">


                            <label for="appointment_id">
                                Appointment
                            </label>


                            <select
                                name="appointment_id"
                                id="appointment_id"
                                required
                            >

                                <option value="">
                                    Select Appointment
                                </option>


                                <?php

                                $sql = "SELECT
                                            appointment_id,
                                            appointment_date,
                                            appointment_time
                                        FROM appointments
                                        WHERE client_id='$client_id'
                                        ORDER BY appointment_id DESC";

                                $result = mysqli_query(
                                    $conn,
                                    $sql
                                );


                                while(
                                    $row = mysqli_fetch_assoc($result)
                                )
                                {

                                ?>

                                    <option
                                        value="<?php
                                        echo htmlspecialchars(
                                            $row["appointment_id"]
                                        );
                                        ?>"
                                    >

                                        Appointment #
                                        <?php
                                        echo htmlspecialchars(
                                            $row["appointment_id"]
                                        );
                                        ?>

                                        -

                                        <?php
                                        echo htmlspecialchars(
                                            $row["appointment_date"]
                                        );
                                        ?>

                                        -

                                        <?php
                                        echo htmlspecialchars(
                                            $row["appointment_time"]
                                        );
                                        ?>

                                    </option>

                                <?php

                                }

                                ?>

                            </select>


                        </div>



                        <!-- =================================================
                             REASON
                             ================================================= -->

                        <div class="form-group">


                            <label for="reason">
                                Reason
                            </label>


                            <select
                                name="reason"
                                id="reason"
                                required
                            >

                                <option value="Fraud">
                                    Fraud
                                </option>

                                <option value="Bad Behaviour">
                                    Bad Behaviour
                                </option>

                                <option value="Misconduct">
                                    Misconduct
                                </option>

                                <option value="Harassment">
                                    Harassment
                                </option>

                                <option value="Other">
                                    Other
                                </option>

                            </select>


                        </div>



                        <!-- =================================================
                             DESCRIPTION
                             ================================================= -->

                        <div class="form-group full-width">


                            <label for="description">
                                Description
                            </label>


                            <textarea
                                name="description"
                                id="description"
                                rows="5"
                                placeholder="Describe the issue or incident..."
                                required
                            ></textarea>


                        </div>


                    </div>



                    <!-- FORM ACTION -->

                    <div class="form-actions">


                        <button
                            type="submit"
                            name="report"
                            class="submit-btn"
                        >

                            <?php echo icon('flag'); ?>

                            Submit Report

                        </button>


                    </div>


                </form>


            </div>


        </section>



        <!-- =====================================================
             SUBMITTED REPORTS
             ===================================================== -->

        <section class="section">


            <div class="section-heading">


                <div class="section-icon">

                    <?php echo icon('folder'); ?>

                </div>


                <div>

                    <h3>My Submitted Reports</h3>

                    <p>
                        Track reports you have submitted to LegalAid.
                    </p>

                </div>


            </div>



            <div class="table-card">


                <div class="table-wrapper">


                    <table>


                        <thead>

                            <tr>

                                <th>
                                    Report ID
                                </th>

                                <th>
                                    Reported User
                                </th>

                                <th>
                                    Appointment
                                </th>

                                <th>
                                    Reason
                                </th>

                                <th>
                                    Description
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Date
                                </th>

                            </tr>

                        </thead>



                        <tbody>


                        <?php

                        $sql = "SELECT
                                    reports.*,
                                    users.name AS reported_name
                                FROM reports
                                JOIN users
                                ON reports.reported_user=users.user_id
                                WHERE reports.reported_by='$client_id'
                                ORDER BY reports.report_id DESC";


                        $result = mysqli_query(
                            $conn,
                            $sql
                        );


                        if(
                            mysqli_num_rows($result) > 0
                        )
                        {

                            while(
                                $row = mysqli_fetch_assoc($result)
                            )
                            {

                        ?>


                            <tr>


                                <!-- REPORT ID -->

                                <td>

                                    <span class="report-id">

                                        #
                                        <?php
                                        echo htmlspecialchars(
                                            $row["report_id"]
                                        );
                                        ?>

                                    </span>

                                </td>



                                <!-- REPORTED USER -->

                                <td>


                                    <div class="user-cell">


                                        <div class="small-avatar">

                                            <?php

                                            echo strtoupper(
                                                substr(
                                                    $row["reported_name"],
                                                    0,
                                                    1
                                                )
                                            );

                                            ?>

                                        </div>


                                        <span>

                                            <?php

                                            echo htmlspecialchars(
                                                $row["reported_name"]
                                            );

                                            ?>

                                        </span>


                                    </div>


                                </td>



                                <!-- APPOINTMENT -->

                                <td>

                                    #

                                    <?php

                                    echo htmlspecialchars(
                                        $row["appointment_id"]
                                    );

                                    ?>

                                </td>



                                <!-- REASON -->

                                <td>


                                    <span class="reason-badge">

                                        <?php

                                        echo htmlspecialchars(
                                            $row["reason"]
                                        );

                                        ?>

                                    </span>


                                </td>



                                <!-- DESCRIPTION -->

                                <td class="description-cell">

                                    <?php

                                    echo htmlspecialchars(
                                        $row["description"]
                                    );

                                    ?>

                                </td>



                                <!-- STATUS -->

                                <td>


                                    <span
                                        class="status-badge
                                        <?php

                                        echo strtolower(
                                            str_replace(
                                                ' ',
                                                '-',
                                                $row["status"]
                                            )
                                        );

                                        ?>"
                                    >

                                        <?php

                                        echo htmlspecialchars(
                                            ucfirst(
                                                $row["status"]
                                            )
                                        );

                                        ?>

                                    </span>


                                </td>



                                <!-- DATE -->

                                <td class="date-cell">

                                    <?php

                                    echo htmlspecialchars(
                                        $row["created_at"]
                                    );

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


                                <td
                                    colspan="7"
                                    class="empty-state"
                                >


                                    <div class="empty-icon">

                                        <?php
                                        echo icon('folder');
                                        ?>

                                    </div>


                                    <strong>
                                        No Reports Submitted
                                    </strong>


                                    <span>
                                        Your submitted reports will appear here.
                                    </span>


                                </td>


                            </tr>


                        <?php

                        }

                        ?>


                        </tbody>


                    </table>


                </div>


            </div>


        </section>



        <!-- =====================================================
             FOOTER
             ===================================================== -->

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