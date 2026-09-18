<?php

session_start();

include("../../db.php");


/* =========================================================
   AUTHENTICATION
   ========================================================= */

if(!isset($_SESSION["id"]))
{
    header("Location: ../../login.php");
    exit();
}

if($_SESSION["role"] != "lawyer")
{
    header("Location: ../../dashboard.php");
    exit();
}


$user_id = $_SESSION["id"];
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


/* =========================================================
   GET LAWYER ID
   ========================================================= */

$sql = "SELECT lawyer_id
        FROM lawyer_profiles
        WHERE user_id='$user_id'";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0)
{
    $row = mysqli_fetch_assoc($result);

    $lawyer_id = $row["lawyer_id"];
}
else
{
    $lawyer_id = 0;
}


/* =========================================================
   SUBMIT REPORT
   ========================================================= */

if(isset($_POST["report"]))
{
    $reported_user = mysqli_real_escape_string(
        $conn,
        $_POST["reported_user"]
    );

    $appointment_id = mysqli_real_escape_string(
        $conn,
        $_POST["appointment_id"]
    );

    $reason = mysqli_real_escape_string(
        $conn,
        $_POST["reason"]
    );

    $description = mysqli_real_escape_string(
        $conn,
        $_POST["description"]
    );


    $sql = "SELECT *
            FROM appointments
            WHERE appointment_id='$appointment_id'
            AND lawyer_id='$lawyer_id'
            AND client_id='$reported_user'";

    $result = mysqli_query($conn, $sql);


    if(mysqli_num_rows($result) > 0)
    {
        $sql2 = "INSERT INTO reports
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
                    '$user_id',
                    '$reported_user',
                    '$appointment_id',
                    '$reason',
                    '$description',
                    'pending'
                )";


        if(mysqli_query($conn, $sql2))
        {
            $message = "Report Submitted Successfully";
            $message_type = "success";
        }
        else
        {
            $message = "Report Submission Failed";
            $message_type = "error";
        }
    }
    else
    {
        $message = "Invalid Appointment";
        $message_type = "error";
    }
}


/* =========================================================
   APPOINTMENT STATISTICS
   ========================================================= */

$sql = "SELECT COUNT(*) AS total
        FROM appointments
        WHERE lawyer_id='$lawyer_id'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$total_appointments = $row["total"];


$sql = "SELECT COUNT(*) AS total
        FROM appointments
        WHERE lawyer_id='$lawyer_id'
        AND status='pending'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$pending_appointments = $row["total"];


$sql = "SELECT COUNT(*) AS total
        FROM appointments
        WHERE lawyer_id='$lawyer_id'
        AND status='accepted'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$accepted_appointments = $row["total"];


$sql = "SELECT COUNT(*) AS total
        FROM appointments
        WHERE lawyer_id='$lawyer_id'
        AND status='rejected'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$rejected_appointments = $row["total"];


$sql = "SELECT COUNT(*) AS total
        FROM appointments
        WHERE lawyer_id='$lawyer_id'
        AND status='completed'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$completed_appointments = $row["total"];


$sql = "SELECT COUNT(*) AS total
        FROM appointments
        WHERE lawyer_id='$lawyer_id'
        AND status='cancelled'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$cancelled_appointments = $row["total"];


/* =========================================================
   CASE STATISTICS
   ========================================================= */

$sql = "SELECT COUNT(*) AS total
        FROM cases
        WHERE lawyer_id='$lawyer_id'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$total_cases = $row["total"];


$sql = "SELECT COUNT(*) AS total
        FROM cases
        WHERE lawyer_id='$lawyer_id'
        AND status='Open'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$open_cases = $row["total"];


$sql = "SELECT COUNT(*) AS total
        FROM cases
        WHERE lawyer_id='$lawyer_id'
        AND status='In Progress'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$in_progress_cases = $row["total"];


$sql = "SELECT COUNT(*) AS total
        FROM cases
        WHERE lawyer_id='$lawyer_id'
        AND status='Completed'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$completed_cases = $row["total"];


$sql = "SELECT COUNT(*) AS total
        FROM cases
        WHERE lawyer_id='$lawyer_id'
        AND status='Closed'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$closed_cases = $row["total"];


/* =========================================================
   CLIENTS
   ========================================================= */

$sql_clients = "SELECT DISTINCT
                    users.user_id,
                    users.name

                FROM appointments

                JOIN users
                ON appointments.client_id=users.user_id

                WHERE appointments.lawyer_id='$lawyer_id'

                AND users.status='active'

                ORDER BY users.name";

$clients_result = mysqli_query(
    $conn,
    $sql_clients
);


/* =========================================================
   APPOINTMENTS
   ========================================================= */

$sql_appointments = "SELECT
                        appointments.*,
                        users.name AS client_name

                      FROM appointments

                      JOIN users
                      ON appointments.client_id=users.user_id

                      WHERE appointments.lawyer_id='$lawyer_id'

                      ORDER BY appointments.appointment_id DESC";

$appointments_result = mysqli_query(
    $conn,
    $sql_appointments
);


/* =========================================================
   SUBMITTED REPORTS
   ========================================================= */

$sql_reports = "SELECT
                    reports.*,
                    users.name AS reported_name

                FROM reports

                JOIN users
                ON reports.reported_user=users.user_id

                WHERE reports.reported_by='$user_id'

                ORDER BY reports.report_id DESC";

$reports_result = mysqli_query(
    $conn,
    $sql_reports
);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Reports — LegalAid</title>

<link rel="icon"
      href="../../logo/favicon-32.png">

<link rel="apple-touch-icon"
      href="../../logo/apple-touch-icon.png">

<link rel="preconnect"
      href="https://fonts.googleapis.com">

<link rel="preconnect"
      href="https://fonts.gstatic.com"
      crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=Inter:wght@400;500;600&display=swap"
      rel="stylesheet">

<link rel="stylesheet"
      href="reports.css">

</head>


<body>


<div class="app">


    <!-- =====================================================
         SIDEBAR
         ===================================================== -->

    <nav class="sidebar">


        <a class="sidebar-logo"
           href="../../dashboard.php">

            <img src="../../logo/logo-icon.svg"
                 alt="LegalAid">

        </a>


        <ul class="sidebar-nav">


            <li title="Dashboard">

                <a href="../../dashboard.php">

                    <?php echo icon('home'); ?>

                </a>

            </li>


            <li title="My Profile">

                <a href="profile.php">

                    <?php echo icon('user'); ?>

                </a>

            </li>


            <li title="Appointments">

                <a href="appointments.php">

                    <?php echo icon('calendar'); ?>

                </a>

            </li>


            <li title="My Cases">

                <a href="cases.php">

                    <?php echo icon('briefcase'); ?>

                </a>

            </li>


            <li class="active"
                title="Reports">

                <a href="reports.php">

                    <?php echo icon('chart'); ?>

                </a>

            </li>


        </ul>


        <div class="sidebar-bottom">

            <a href="../../logout.php"
               title="Logout">

                <?php echo icon('logout'); ?>

            </a>

        </div>


    </nav>



    <!-- =====================================================
         MAIN
         ===================================================== -->

    <main class="main">


        <header class="topbar">


            <div>

                <a class="back-link"
                   href="../../dashboard.php">

                    <?php echo icon('back'); ?>

                    Back to Dashboard

                </a>


                <h1>
                    Lawyer Reports
                </h1>


                <p class="subtitle">
                    Review your activity and submit reports when necessary.
                </p>

            </div>


            <div class="account">


                <div class="account-info">

                    <span class="account-name">

                        <?php
                        echo htmlspecialchars($username);
                        ?>

                    </span>

                    <span class="account-role">
                        Lawyer
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



        <?php if($message != ""): ?>

            <div class="message <?php echo $message_type; ?>">

                <?php
                echo htmlspecialchars($message);
                ?>

            </div>

        <?php endif; ?>



        <!-- APPOINTMENT SUMMARY -->

        <section class="report-card">


            <div class="card-header">

                <div>

                    <h2>
                        Appointment Summary
                    </h2>

                    <p>
                        Overview of your appointment activity.
                    </p>

                </div>

            </div>


            <div class="stats-grid">


                <div class="stat-item">

                    <span class="stat-label">
                        Total
                    </span>

                    <strong class="stat-value">
                        <?php echo $total_appointments; ?>
                    </strong>

                </div>


                <div class="stat-item">

                    <span class="stat-label">
                        Pending
                    </span>

                    <strong class="stat-value">
                        <?php echo $pending_appointments; ?>
                    </strong>

                </div>


                <div class="stat-item">

                    <span class="stat-label">
                        Accepted
                    </span>

                    <strong class="stat-value">
                        <?php echo $accepted_appointments; ?>
                    </strong>

                </div>


                <div class="stat-item">

                    <span class="stat-label">
                        Rejected
                    </span>

                    <strong class="stat-value">
                        <?php echo $rejected_appointments; ?>
                    </strong>

                </div>


                <div class="stat-item">

                    <span class="stat-label">
                        Completed
                    </span>

                    <strong class="stat-value">
                        <?php echo $completed_appointments; ?>
                    </strong>

                </div>


                <div class="stat-item">

                    <span class="stat-label">
                        Cancelled
                    </span>

                    <strong class="stat-value">
                        <?php echo $cancelled_appointments; ?>
                    </strong>

                </div>


            </div>


        </section>



        <!-- CASE SUMMARY -->

        <section class="report-card">


            <div class="card-header">

                <div>

                    <h2>
                        Case Summary
                    </h2>

                    <p>
                        Overview of your current and completed cases.
                    </p>

                </div>

            </div>


            <div class="stats-grid case-stats">


                <div class="stat-item">

                    <span class="stat-label">
                        Total Cases
                    </span>

                    <strong class="stat-value">
                        <?php echo $total_cases; ?>
                    </strong>

                </div>


                <div class="stat-item">

                    <span class="stat-label">
                        Open
                    </span>

                    <strong class="stat-value">
                        <?php echo $open_cases; ?>
                    </strong>

                </div>


                <div class="stat-item">

                    <span class="stat-label">
                        In Progress
                    </span>

                    <strong class="stat-value">
                        <?php echo $in_progress_cases; ?>
                    </strong>

                </div>


                <div class="stat-item">

                    <span class="stat-label">
                        Completed
                    </span>

                    <strong class="stat-value">
                        <?php echo $completed_cases; ?>
                    </strong>

                </div>


                <div class="stat-item">

                    <span class="stat-label">
                        Closed
                    </span>

                    <strong class="stat-value">
                        <?php echo $closed_cases; ?>
                    </strong>

                </div>


            </div>


        </section>



        <!-- REPORT CLIENT -->

        <section class="report-card">


            <div class="card-header">

                <div>

                    <h2>
                        Report a Client
                    </h2>

                    <p>
                        Submit a report regarding a client or appointment.
                    </p>

                </div>

            </div>


            <form method="post">


                <div class="form-grid">


                    <div class="form-group">

                        <label for="reported_user">
                            Select Client
                        </label>

                        <select
                            name="reported_user"
                            id="reported_user"
                            required
                        >

                            <option value="">
                                Select Client
                            </option>


                            <?php

                            if(
                                $clients_result &&
                                mysqli_num_rows($clients_result) > 0
                            ):

                                while(
                                    $client =
                                    mysqli_fetch_assoc($clients_result)
                                ):

                            ?>

                            <option
                                value="<?php
                                echo htmlspecialchars(
                                    $client["user_id"]
                                );
                                ?>"
                            >

                                <?php
                                echo htmlspecialchars(
                                    $client["name"]
                                );
                                ?>

                            </option>

                            <?php

                                endwhile;

                            endif;

                            ?>

                        </select>

                    </div>



                    <div class="form-group">

                        <label for="appointment_id">
                            Select Appointment
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

                            if(
                                $appointments_result &&
                                mysqli_num_rows($appointments_result) > 0
                            ):

                                while(
                                    $appointment =
                                    mysqli_fetch_assoc(
                                        $appointments_result
                                    )
                                ):

                            ?>

                            <option
                                value="<?php
                                echo htmlspecialchars(
                                    $appointment["appointment_id"]
                                );
                                ?>"
                            >

                                <?php

                                echo htmlspecialchars(
                                    $appointment["client_name"]
                                );

                                echo " — ";

                                echo htmlspecialchars(
                                    $appointment["appointment_date"]
                                );

                                echo " ";

                                echo htmlspecialchars(
                                    $appointment["appointment_time"]
                                );

                                ?>

                            </option>

                            <?php

                                endwhile;

                            endif;

                            ?>

                        </select>

                    </div>



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



                    <div class="form-group full-width">

                        <label for="description">
                            Description
                        </label>

                        <textarea
                            name="description"
                            id="description"
                            rows="6"
                            placeholder="Describe the issue or incident..."
                            required
                        ></textarea>

                    </div>


                </div>


                <div class="form-actions">

                    <button
                        type="submit"
                        name="report"
                        class="button button-primary"
                    >

                        Submit Report

                    </button>

                </div>


            </form>


        </section>



        <!-- SUBMITTED REPORTS -->

        <section class="report-card">


            <div class="card-header">

                <div>

                    <h2>
                        My Submitted Reports
                    </h2>

                    <p>
                        View reports you have previously submitted.
                    </p>

                </div>

            </div>


            <div class="table-wrapper">


                <table>


                    <thead>

                        <tr>

                            <th>Report ID</th>

                            <th>Client</th>

                            <th>Appointment</th>

                            <th>Reason</th>

                            <th>Description</th>

                            <th>Status</th>

                            <th>Date</th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php

                    if(
                        $reports_result &&
                        mysqli_num_rows($reports_result) > 0
                    ):

                        while(
                            $report =
                            mysqli_fetch_assoc($reports_result)
                        ):

                    ?>


                    <tr>


                        <td>

                            <span class="report-id">

                                #

                                <?php
                                echo htmlspecialchars(
                                    $report["report_id"]
                                );
                                ?>

                            </span>

                        </td>


                        <td>

                            <div class="person-cell">

                                <span class="person-avatar">

                                    <?php

                                    echo strtoupper(
                                        substr(
                                            $report["reported_name"],
                                            0,
                                            1
                                        )
                                    );

                                    ?>

                                </span>

                                <span>

                                    <?php

                                    echo htmlspecialchars(
                                        $report["reported_name"]
                                    );

                                    ?>

                                </span>

                            </div>

                        </td>


                        <td>

                            <span class="appointment-id">

                                #

                                <?php

                                echo htmlspecialchars(
                                    $report["appointment_id"]
                                );

                                ?>

                            </span>

                        </td>


                        <td>

                            <span class="tag">

                                <?php

                                echo htmlspecialchars(
                                    $report["reason"]
                                );

                                ?>

                            </span>

                        </td>


                        <td class="description-cell">

                            <?php

                            echo htmlspecialchars(
                                $report["description"]
                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            $status_class =
                                strtolower(
                                    str_replace(
                                        " ",
                                        "-",
                                        $report["status"]
                                    )
                                );

                            ?>

                            <span
                                class="status-tag <?php
                                echo htmlspecialchars(
                                    $status_class
                                );
                                ?>"
                            >

                                <?php

                                echo htmlspecialchars(
                                    $report["status"]
                                );

                                ?>

                            </span>

                        </td>


                        <td class="date-cell">

                            <?php

                            echo htmlspecialchars(
                                $report["created_at"]
                            );

                            ?>

                        </td>


                    </tr>


                    <?php

                        endwhile;

                    else:

                    ?>


                    <tr>

                        <td
                            colspan="7"
                            class="empty-state"
                        >

                            No Reports Submitted

                        </td>

                    </tr>


                    <?php

                    endif;

                    ?>


                    </tbody>

                </table>


            </div>


        </section>



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