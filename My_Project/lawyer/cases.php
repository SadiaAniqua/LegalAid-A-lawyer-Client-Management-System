<?php

session_start();

include("../db.php");

if(!isset($_SESSION["id"]))
{
    header("Location: ../login.php");
    exit();
}

if($_SESSION["role"] != "lawyer")
{
    header("Location: ../dashboard.php");
    exit();
}

$lawyer_user_id = $_SESSION["id"];
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
   FIND LAWYER ID
   ========================================================= */

$sql2 = "SELECT lawyer_id
         FROM lawyer_profiles
         WHERE user_id='$lawyer_user_id'";

$result2 = mysqli_query($conn, $sql2);

$lawyer = mysqli_fetch_assoc($result2);

if(!$lawyer)
{
    $lawyer_id = 0;
}
else
{
    $lawyer_id = $lawyer["lawyer_id"];
}


/* =========================================================
   UPDATE CASE STATUS
   ========================================================= */

if(isset($_POST["update"]))
{
    $case_id = mysqli_real_escape_string(
        $conn,
        $_POST["case_id"]
    );

    $status = mysqli_real_escape_string(
        $conn,
        $_POST["status"]
    );

    $sql = "UPDATE cases
            SET status='$status'
            WHERE case_id='$case_id'
            AND lawyer_id='$lawyer_id'";

    if(mysqli_query($conn, $sql))
    {
        $message = "Case Status Updated Successfully";
        $message_type = "success";
    }
    else
    {
        $message = "Case Status Update Failed";
        $message_type = "error";
    }
}


/* =========================================================
   CREATE CASE
   ========================================================= */

if(isset($_POST["create"]))
{
    $appointment_id = mysqli_real_escape_string(
        $conn,
        $_POST["appointment_id"]
    );

    $case_title = mysqli_real_escape_string(
        $conn,
        $_POST["case_title"]
    );

    $case_type = mysqli_real_escape_string(
        $conn,
        $_POST["case_type"]
    );

    $case_description = mysqli_real_escape_string(
        $conn,
        $_POST["case_description"]
    );


    $sql = "SELECT *
            FROM appointments
            WHERE appointment_id='$appointment_id'
            AND lawyer_id='$lawyer_id'
            AND status='accepted'";

    $result = mysqli_query($conn, $sql);


    if(mysqli_num_rows($result) > 0)
    {
        $appointment = mysqli_fetch_assoc($result);

        $client_id = $appointment["client_id"];


        $sql3 = "INSERT INTO cases
                (
                    client_id,
                    lawyer_id,
                    appointment_id,
                    case_title,
                    case_type,
                    case_description,
                    status
                )

                VALUES
                (
                    '$client_id',
                    '$lawyer_id',
                    '$appointment_id',
                    '$case_title',
                    '$case_type',
                    '$case_description',
                    'Open'
                )";


        if(mysqli_query($conn, $sql3))
        {
            $message = "Case Created Successfully";
            $message_type = "success";
        }
        else
        {
            $message = "Case Creation Failed";
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
   GET ALL CASES
   ========================================================= */

$sql = "SELECT cases.*,
        users.name AS client_name

        FROM cases

        JOIN users
        ON cases.client_id=users.user_id

        WHERE cases.lawyer_id='$lawyer_id'

        ORDER BY cases.case_id DESC";

$result = mysqli_query($conn, $sql);


/* =========================================================
   GET ACCEPTED APPOINTMENTS
   ========================================================= */

$sql_appointments = "SELECT appointments.*,
                     users.name AS client_name

                     FROM appointments

                     JOIN users
                     ON appointments.client_id=users.user_id

                     WHERE appointments.lawyer_id='$lawyer_id'

                     AND appointments.status='accepted'

                     ORDER BY appointments.appointment_date,
                     appointments.appointment_time";

$accepted_appointments = mysqli_query(
    $conn,
    $sql_appointments
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>My Cases — LegalAid</title>

<link rel="icon"
      href="../logo/favicon-32.png">

<link rel="apple-touch-icon"
      href="../logo/apple-touch-icon.png">

<link rel="preconnect"
      href="https://fonts.googleapis.com">

<link rel="preconnect"
      href="https://fonts.gstatic.com"
      crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=Inter:wght@400;500;600&display=swap"
      rel="stylesheet">

<link rel="stylesheet"
      href="cases.css">

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

            <li title="My Profile">

                <a href="profile.php">

                    <?php echo icon('user'); ?>

                </a>

            </li>


            <!-- APPOINTMENTS -->

            <li title="Appointments">

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
             SAME STRUCTURE AS PROFILE.PHP
             ================================================= -->

        <header class="topbar">


            <div class="page-heading">


                <!-- BACK TO DASHBOARD -->

                <a class="back-link"
                   href="../dashboard.php">

                    <?php echo icon('back'); ?>

                    Back to Dashboard

                </a>


                <!-- PAGE TITLE -->

                <h1>
                    My Cases
                </h1>


                <!-- SUBTITLE -->

                <p class="subtitle">
                    Manage your cases and track their current status.
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
             ALL CASES
             ================================================= -->

        <section class="section-block">


            <div class="section-header">

                <div>

                    <h3>
                        All My Cases
                    </h3>

                    <p>
                        View and update the status of your active cases.
                    </p>

                </div>

            </div>


            <section class="results-card">


                <div class="table-wrapper">


                    <table>


                        <thead>

                            <tr>

                                <th>
                                    Case ID
                                </th>

                                <th>
                                    Client
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

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                        <?php

                        if(
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

                                <span class="case-id">

                                    #
                                    <?php
                                    echo htmlspecialchars(
                                        $row["case_id"]
                                    );
                                    ?>

                                </span>

                            </td>


                            <!-- CLIENT -->

                            <td>

                                <div class="person-cell">

                                    <span class="person-avatar">

                                        <?php
                                        echo strtoupper(
                                            substr(
                                                $row["client_name"],
                                                0,
                                                1
                                            )
                                        );
                                        ?>

                                    </span>

                                    <span>

                                        <?php
                                        echo htmlspecialchars(
                                            $row["client_name"]
                                        );
                                        ?>

                                    </span>

                                </div>

                            </td>


                            <!-- TITLE -->

                            <td>

                                <strong>

                                    <?php
                                    echo htmlspecialchars(
                                        $row["case_title"]
                                    );
                                    ?>

                                </strong>

                            </td>


                            <!-- TYPE -->

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

                                <span class="status-tag">

                                    <?php
                                    echo htmlspecialchars(
                                        $row["status"]
                                    );
                                    ?>

                                </span>

                            </td>


                            <!-- ACTION -->

                            <td class="action-cell">


                                <form method="post"
                                      class="status-form">


                                    <input
                                        type="hidden"
                                        name="case_id"
                                        value="<?php
                                        echo htmlspecialchars(
                                            $row["case_id"]
                                        );
                                        ?>"
                                    >


                                    <select
                                        name="status"
                                        class="status-select"
                                    >

                                        <option
                                            value="Open"
                                            <?php
                                            if(
                                                $row["status"] ==
                                                "Open"
                                            )
                                            {
                                                echo "selected";
                                            }
                                            ?>
                                        >
                                            Open
                                        </option>


                                        <option
                                            value="In Progress"
                                            <?php
                                            if(
                                                $row["status"] ==
                                                "In Progress"
                                            )
                                            {
                                                echo "selected";
                                            }
                                            ?>
                                        >
                                            In Progress
                                        </option>


                                        <option
                                            value="Completed"
                                            <?php
                                            if(
                                                $row["status"] ==
                                                "Completed"
                                            )
                                            {
                                                echo "selected";
                                            }
                                            ?>
                                        >
                                            Completed
                                        </option>


                                        <option
                                            value="Closed"
                                            <?php
                                            if(
                                                $row["status"] ==
                                                "Closed"
                                            )
                                            {
                                                echo "selected";
                                            }
                                            ?>
                                        >
                                            Closed
                                        </option>

                                    </select>


                                    <button
                                        type="submit"
                                        name="update"
                                        class="button button-small"
                                    >
                                        Update
                                    </button>


                                </form>


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

                                No cases found.

                            </td>

                        </tr>


                        <?php

                        endif;

                        ?>


                        </tbody>

                    </table>

                </div>

            </section>

        </section>



        <!-- =================================================
             CREATE NEW CASE
             ================================================= -->

        <section class="create-card">


            <div class="section-header">

                <div>

                    <h3>
                        Create New Case
                    </h3>

                    <p>
                        Create a case using one of your accepted appointments.
                    </p>

                </div>

            </div>


            <form method="post"
                  class="case-form">


                <!-- ACCEPTED APPOINTMENT -->

                <div class="form-group full-width">

                    <label for="appointment_id">
                        Accepted Appointment
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
                            $accepted_appointments &&
                            mysqli_num_rows(
                                $accepted_appointments
                            ) > 0
                        ):

                            while(
                                $appointment =
                                mysqli_fetch_assoc(
                                    $accepted_appointments
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


                <!-- TITLE + TYPE -->

                <div class="form-row">


                    <div class="form-group">

                        <label for="case_title">
                            Case Title
                        </label>

                        <input
                            type="text"
                            name="case_title"
                            id="case_title"
                            placeholder="Enter case title"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="case_type">
                            Case Type
                        </label>

                        <input
                            type="text"
                            name="case_type"
                            id="case_type"
                            placeholder="e.g. Criminal, Civil, Family"
                            required
                        >

                    </div>


                </div>


                <!-- DESCRIPTION -->

                <div class="form-group">

                    <label for="case_description">
                        Case Description
                    </label>

                    <textarea
                        name="case_description"
                        id="case_description"
                        rows="5"
                        placeholder="Enter a description of the case"
                        required
                    ></textarea>

                </div>


                <!-- FORM ACTION -->

                <div class="form-actions">

                    <button
                        type="submit"
                        name="create"
                        class="button button-primary"
                    >
                        Create Case
                    </button>

                </div>


            </form>


        </section>



        <!-- FOOTER -->

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