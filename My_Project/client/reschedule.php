
<?php

session_start();

include("../db.php");

if(!isset($_SESSION["id"]))
{
    header("Location: ../login.php");
    exit();
}

$client_id = $_SESSION["id"];

$appointment_id = $_GET["id"];

$message = "";
$message_type = "";

if(isset($_POST["reschedule"]))
{
    $new_date = $_POST["new_date"];
    $new_time = $_POST["new_time"];

    $sql = "UPDATE appointments

            SET appointment_date='$new_date',
                appointment_time='$new_time',
                status='pending'

            WHERE appointment_id='$appointment_id'
            AND client_id='$client_id'
            AND status='accepted'";

    if(mysqli_query($conn, $sql))
    {
        $message = "Reschedule request sent successfully.";
        $message_type = "success";
    }
    else
    {
        $message = "Reschedule failed. Please try again.";
        $message_type = "error";
    }
}

?>

<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reschedule Appointment | LegalAid</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Sora:wght@500;600;700&display=swap"
        rel="stylesheet"
    >

    <!-- Remix Icon -->
    <link
        href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="rescheduleAppointment.css">

</head>

<body>

<div class="app">

    <!-- ================= SIDEBAR ================= -->

    <aside class="sidebar">

        <div class="logo">
            <i class="ri-scales-3-line"></i>
        </div>

        <nav class="sidebar-nav">

            <a href="dashboard.php" title="Dashboard">
                <i class="ri-dashboard-line"></i>
            </a>

            <a href="profile.php" title="Profile">
                <i class="ri-user-line"></i>
            </a>

            <a href="findLawyer.php" title="Find Lawyer">
                <i class="ri-search-line"></i>
            </a>

            <a href="appointments.php" class="active" title="Appointments">
                <i class="ri-calendar-check-line"></i>
            </a>

            <a href="cases.php" title="Cases">
                <i class="ri-briefcase-line"></i>
            </a>

            <a href="reports.php" title="Reports">
                <i class="ri-file-chart-line"></i>
            </a>

        </nav>

        <div class="sidebar-bottom">

            <a href="../logout.php" title="Logout">
                <i class="ri-logout-box-r-line"></i>
            </a>

        </div>

    </aside>


    <!-- ================= MAIN ================= -->

    <main class="main">

        <!-- TOPBAR -->

        <header class="topbar">

            <div>

                <div class="brand">
                    LegalAid
                </div>

                <div class="subtitle">
                    Reschedule Appointment
                </div>

            </div>


            <div class="account">

                <div class="account-info">

                    <span class="account-name">
                        <?php echo htmlspecialchars($_SESSION["username"] ?? "Client"); ?>
                    </span>

                    <span class="account-role">
                        Client
                    </span>

                </div>

                <div class="avatar">
                    <i class="ri-user-line"></i>
                </div>

            </div>

        </header>
		







        <!-- PAGE HEADING -->

        <section class="page-heading">

            <div>

                <span class="eyebrow">
                    APPOINTMENTS
                </span>

                <h1>
                    Reschedule Appointment
                </h1>

                <p>
                    Choose a new date and time for your appointment.
                </p>

            </div>

        </section>
		
		

<a href="appointments.php" class="back-link">
    <i class="ri-arrow-left-line"></i>
    Back to Appointments
</a>

        <!-- RESCHEDULE CARD -->

        <section class="reschedule-card">

            <div class="card-header">

                <div class="header-icon">
                    <i class="ri-calendar-edit-line"></i>
                </div>

                <div>

                    <h2>
                        Request a New Schedule
                    </h2>

                    <p>
                        Select your preferred date and time below.
                    </p>

                </div>

            </div>


            <?php if($message != "") { ?>

                <div class="message <?php echo $message_type; ?>">

                    <?php if($message_type == "success") { ?>

                        <i class="ri-checkbox-circle-line"></i>

                    <?php } else { ?>

                        <i class="ri-error-warning-line"></i>

                    <?php } ?>

                    <span>
                        <?php echo htmlspecialchars($message); ?>
                    </span>

                </div>

            <?php } ?>


            <form method="post" class="reschedule-form">

                <div class="form-grid">

                    <!-- DATE -->

                    <div class="form-group">

                        <label for="new_date">
                            New Date
                        </label>

                        <div class="input-wrapper">

                            <i class="ri-calendar-line"></i>

                            <input
                                type="date"
                                id="new_date"
                                name="new_date"
                                required
                            >

                        </div>

                    </div>


                    <!-- TIME -->

                    <div class="form-group">

                        <label for="new_time">
                            New Time
                        </label>

                        <div class="input-wrapper">

                            <i class="ri-time-line"></i>

                            <input
                                type="time"
                                id="new_time"
                                name="new_time"
                                required
                            >

                        </div>

                    </div>

                </div>


                <div class="form-note">

                    <i class="ri-information-line"></i>

                    <span>
                        Your reschedule request will be sent for approval.
                    </span>

                </div>


                <div class="form-actions">

                    <a href="appointments.php" class="btn btn-secondary">

                        <i class="ri-arrow-left-line"></i>

                        Back to Appointments

                    </a>


                    <button
                        type="submit"
                        name="reschedule"
                        class="btn btn-primary"
                    >

                        <i class="ri-calendar-check-line"></i>

                        Request Reschedule

                    </button>

                </div>

            </form>

        </section>


        <!-- FOOTER -->

        <footer class="footer">

            <span>
                LegalAid
            </span>

            <span>
                Legal consultation made simple.
            </span>

        </footer>

    </main>

</div>

</body>

</html>

