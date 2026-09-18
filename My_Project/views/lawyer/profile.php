<?php

session_start();

include("../../db.php");

if (!isset($_SESSION["id"])) {
    header("Location: ../../login.php");
    exit();
}

if ($_SESSION["role"] != "lawyer") {
    header("Location: ../../dashboard.php");
    exit();
}

$user_id = $_SESSION["id"];
$username = $_SESSION["username"];

$message = "";
$messageType = "";


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
   SAVE PROFILE
   ========================================================= */

if (isset($_POST["save"])) {

    $specialization_id = $_POST["specialization_id"];
    $experience = $_POST["experience"];
    $chamber_name = $_POST["chamber_name"];
    $address = $_POST["address"];
    $bio = $_POST["bio"];
    $consultation_fee = $_POST["consultation_fee"];

    $sql = "SELECT * FROM lawyer_profiles
            WHERE user_id='$user_id'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {

        $sql = "UPDATE lawyer_profiles SET
                specialization_id='$specialization_id',
                experience='$experience',
                chamber_name='$chamber_name',
                address='$address',
                bio='$bio',
                consultation_fee='$consultation_fee'
                WHERE user_id='$user_id'";

    } else {

        $sql = "INSERT INTO lawyer_profiles
                (
                    user_id,
                    specialization_id,
                    experience,
                    chamber_name,
                    address,
                    bio,
                    consultation_fee,
                    verification_status
                )
                VALUES
                (
                    '$user_id',
                    '$specialization_id',
                    '$experience',
                    '$chamber_name',
                    '$address',
                    '$bio',
                    '$consultation_fee',
                    'pending'
                )";
    }

    if (mysqli_query($conn, $sql)) {

        $message = "Profile saved successfully.";
        $messageType = "success";

    } else {

        $message = "Profile save failed.";
        $messageType = "error";
    }
}


/* =========================================================
   GET LAWYER INFORMATION
   ========================================================= */

$sql = "SELECT
            lawyer_profiles.*,
            users.name,
            users.email,
            users.phone,
            specializations.name AS specialization_name

        FROM lawyer_profiles

        JOIN users
        ON lawyer_profiles.user_id = users.user_id

        LEFT JOIN specializations
        ON lawyer_profiles.specialization_id =
           specializations.specialization_id

        WHERE lawyer_profiles.user_id='$user_id'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {

    $row = mysqli_fetch_assoc($result);

} else {

    $row = array();

    $row["name"] = $_SESSION["username"];
    $row["email"] = "";
    $row["phone"] = "";
    $row["specialization_id"] = "";
    $row["experience"] = "";
    $row["chamber_name"] = "";
    $row["address"] = "";
    $row["bio"] = "";
    $row["consultation_fee"] = "";
    $row["verification_status"] = "Not Submitted";
}


/* =========================================================
   GET SPECIALIZATIONS
   ========================================================= */

$sql2 = "SELECT * FROM specializations ORDER BY name";

$result2 = mysqli_query($conn, $sql2);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>My Profile — LegalAid</title>

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
          href="profile.css">

</head>


<body>

<div class="app">


    <!-- =====================================================
         SIDEBAR
         ===================================================== -->

    <nav class="sidebar">

        <!-- LOGO -->

        <a class="sidebar-logo"
           href="../../dashboard.php">

            <img src="../../logo/logo-icon.svg"
                 alt="LegalAid">

        </a>


        <!-- NAVIGATION -->

        <ul class="sidebar-nav">

            <!-- DASHBOARD -->

            <li title="Dashboard">

                <a href="../../dashboard.php">

                    <?php echo icon('home'); ?>

                </a>

            </li>


            <!-- PROFILE -->

            <li class="active"
                title="My Profile">

                <a href="profile.php">

                    <?php echo icon('user'); ?>

                </a>

            </li>


            <!-- APPOINTMENTS -->

            <li title="Client Bookings">

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

            <a href="../../logout.php"
               title="Logout">

                <?php echo icon('logout'); ?>

            </a>

        </div>

    </nav>


    <!-- =====================================================
         MAIN CONTENT
         ===================================================== -->

    <main class="main">


        <!-- =================================================
             TOPBAR
             ================================================= -->

        <header class="topbar">

            <div class="page-heading">

                <a class="back-link"
                   href="../../dashboard.php">

                    <?php echo icon('back'); ?>

                    <span>Back to Dashboard</span>

                </a>

                <h1>My Lawyer Profile</h1>

                <p class="subtitle">
                    Manage your professional information and consultation details.
                </p>

            </div>


            <!-- ACCOUNT -->

            <div class="account">

                <div class="account-info">

                    <span class="account-name">
                        <?php echo htmlspecialchars($username); ?>
                    </span>

                    <span class="account-role">
                        Lawyer
                    </span>

                </div>

                <div class="avatar">

                    <?php
                    echo strtoupper(substr($username, 0, 1));
                    ?>

                </div>

            </div>

        </header>


        <!-- =================================================
             MESSAGE
             ================================================= -->

        <?php if ($message != ""): ?>

            <div class="message <?php echo $messageType; ?>">

                <?php echo htmlspecialchars($message); ?>

            </div>

        <?php endif; ?>


        <!-- =================================================
             PERSONAL INFORMATION
             ================================================= -->

        <section class="profile-card">

            <div class="card-header">

                <div>

                    <h2>Personal Information</h2>

                    <p>
                        Your basic account information.
                    </p>

                </div>

            </div>


            <div class="info-grid">

                <div class="info-item">

                    <span class="info-label">
                        Full Name
                    </span>

                    <span class="info-value">
                        <?php echo htmlspecialchars($row["name"]); ?>
                    </span>

                </div>


                <div class="info-item">

                    <span class="info-label">
                        Email
                    </span>

                    <span class="info-value">
                        <?php echo htmlspecialchars($row["email"]); ?>
                    </span>

                </div>


                <div class="info-item">

                    <span class="info-label">
                        Phone
                    </span>

                    <span class="info-value">
                        <?php echo htmlspecialchars($row["phone"]); ?>
                    </span>

                </div>


                <div class="info-item">

                    <span class="info-label">
                        Verification Status
                    </span>

                    <span class="status-tag
                        <?php
                        echo strtolower(
                            str_replace(
                                ' ',
                                '-',
                                $row["verification_status"]
                            )
                        );
                        ?>">

                        <?php
                        echo htmlspecialchars(
                            $row["verification_status"]
                        );
                        ?>

                    </span>

                </div>

            </div>

        </section>


        <!-- =================================================
             PROFESSIONAL INFORMATION
             ================================================= -->

        <section class="profile-card">

            <div class="card-header">

                <div>

                    <h2>Professional Information</h2>

                    <p>
                        Keep your lawyer profile up to date so clients can find you.
                    </p>

                </div>

            </div>


            <form method="post">

                <div class="form-grid">


                    <!-- SPECIALIZATION -->

                    <div class="form-group">

                        <label for="specialization_id">
                            Specialization
                        </label>

                        <select
                            id="specialization_id"
                            name="specialization_id"
                            required
                        >

                            <option value="">
                                Select specialization
                            </option>

                            <?php while (
                                $specialization =
                                mysqli_fetch_assoc($result2)
                            ): ?>

                                <option
                                    value="<?php
                                    echo htmlspecialchars(
                                        $specialization["specialization_id"]
                                    );
                                    ?>"

                                    <?php

                                    if (
                                        isset($row["specialization_id"]) &&
                                        $row["specialization_id"] ==
                                        $specialization["specialization_id"]
                                    ) {
                                        echo "selected";
                                    }

                                    ?>
                                >

                                    <?php
                                    echo htmlspecialchars(
                                        $specialization["name"]
                                    );
                                    ?>

                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>


                    <!-- EXPERIENCE -->

                    <div class="form-group">

                        <label for="experience">
                            Experience
                        </label>

                        <div class="input-with-text">

                            <input
                                type="number"
                                id="experience"
                                name="experience"
                                min="0"
                                value="<?php
                                echo htmlspecialchars(
                                    $row["experience"]
                                );
                                ?>"
                                required
                            >

                            <span>years</span>

                        </div>

                    </div>


                    <!-- CHAMBER -->

                    <div class="form-group">

                        <label for="chamber_name">
                            Chamber Name
                        </label>

                        <input
                            type="text"
                            id="chamber_name"
                            name="chamber_name"
                            value="<?php
                            echo htmlspecialchars(
                                $row["chamber_name"]
                            );
                            ?>"
                            placeholder="Enter chamber name"
                            required
                        >

                    </div>


                    <!-- ADDRESS -->

                    <div class="form-group">

                        <label for="address">
                            Chamber Address
                        </label>

                        <input
                            type="text"
                            id="address"
                            name="address"
                            value="<?php
                            echo htmlspecialchars(
                                $row["address"]
                            );
                            ?>"
                            placeholder="Enter chamber address"
                            required
                        >

                    </div>


                    <!-- CONSULTATION FEE -->

                    <div class="form-group">

                        <label for="consultation_fee">
                            Consultation Fee
                        </label>

                        <div class="input-with-text">

                            <input
                                type="number"
                                id="consultation_fee"
                                name="consultation_fee"
                                min="0"
                                value="<?php
                                echo htmlspecialchars(
                                    $row["consultation_fee"]
                                );
                                ?>"
                                placeholder="Enter fee"
                                required
                            >

                            <span>BDT</span>

                        </div>

                    </div>


                    <!-- BIO -->

                    <div class="form-group full-width">

                        <label for="bio">
                            Professional Bio
                        </label>

                        <textarea
                            id="bio"
                            name="bio"
                            rows="6"
                            placeholder="Write a short professional biography..."
                            required
                        ><?php
                        echo htmlspecialchars(
                            $row["bio"]
                        );
                        ?></textarea>

                    </div>

                </div>


                <!-- FORM ACTIONS -->

                <div class="form-actions">

                    <button
                        type="submit"
                        name="save"
                        class="button button-primary"
                    >
                        Save Profile
                    </button>

                </div>

            </form>

        </section>


        <!-- =================================================
             FOOTER
             ================================================= -->

        <footer class="footer">

            &copy; <?php echo date("Y"); ?>
            LegalAid. All rights reserved.

        </footer>


    </main>

</div>

</body>
</html>