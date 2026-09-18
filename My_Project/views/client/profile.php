<?php

session_start();

if(!isset($_SESSION["id"]))
{
    header("Location: ../../login.php");
    exit;
}

if($_SESSION["role"] != "client")
{
    header("Location: ../../dashboard.php");
    exit;
}

include("../../db.php");

$user_id = $_SESSION["id"];

$username = $_SESSION["username"] ?? "";

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

        'logout' =>
        '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
         <path d="M16 17l5-5-5-5"/>
         <path d="M21 12H9"/>',

        'edit' =>
        '<path d="M12 20h9"/>
         <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4z"/>',

        'mail' =>
        '<rect x="3" y="5" width="18" height="14" rx="2"/>
         <path d="m3 7 9 6 9-6"/>',

        'phone' =>
        '<path d="M6.5 3.5 9 3l2 5-2.2 1.7a14 14 0 0 0 5 5L15.5 13l5 2 .5 2.5a2 2 0 0 1-2 2C10.7 19.5 4.5 13.3 4.5 5.5a2 2 0 0 1 2-2z"/>',

        'shield' =>
        '<path d="M12 3.5 19 6v6c0 4.4-3 7.8-7 8.5-4-.7-7-4.1-7-8.5V6z"/>
         <path d="m9 12 2 2 4-4.2"/>'
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
   UPDATE PROFILE
   ========================================================= */

if(isset($_POST["update"]))
{
    $name = mysqli_real_escape_string(
        $conn,
        trim($_POST["name"] ?? "")
    );

    $email = mysqli_real_escape_string(
        $conn,
        trim($_POST["email"] ?? "")
    );

    $phone = mysqli_real_escape_string(
        $conn,
        trim($_POST["phone"] ?? "")
    );


    if(empty($name) || empty($email) || empty($phone))
    {
        $message = "Please complete all required fields.";
        $message_type = "error";
    }
    else
    {
        $sql = "UPDATE users
                SET name='$name',
                    email='$email',
                    phone='$phone'
                WHERE user_id='$user_id'";


        if(mysqli_query($conn, $sql))
        {
            $_SESSION["username"] = $name;

            $username = $name;

            $message = "Profile updated successfully.";
            $message_type = "success";
        }
        else
        {
            $message = "Profile update failed.";
            $message_type = "error";
        }
    }
}


/* =========================================================
   GET PROFILE
   ========================================================= */

$sql = "SELECT *
        FROM users
        WHERE user_id='$user_id'";

$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_assoc($result);


/* =========================================================
   FALLBACK
   ========================================================= */

if(!$row)
{
    $row = [
        "user_id" => $user_id,
        "name" => "",
        "email" => "",
        "phone" => "",
        "role" => "client",
        "status" => "",
        "created_at" => ""
    ];
}

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


        <!-- Logo -->

        <a class="sidebar-logo"
           href="../../dashboard.php">

            <img src="../../logo/logo-icon.svg"
                 alt="LegalAid">

        </a>


        <ul class="sidebar-nav">


            <!-- Dashboard -->

            <li title="Dashboard">

                <a href="../../dashboard.php">

                    <?php echo icon('home'); ?>

                </a>

            </li>


            <!-- Profile -->

            <li class="active"
                title="Edit Profile">

                <a href="profile.php">

                    <?php echo icon('user'); ?>

                </a>

            </li>


            <!-- Find Lawyer -->

            <li title="Find Lawyer">

                <a href="../../client/findLawyer.php">

                    <?php echo icon('search'); ?>

                </a>

            </li>


            <!-- Appointments -->

            <li title="My Appointments">

                <a href="appointments.php">

                    <?php echo icon('calendar'); ?>

                </a>

            </li>


            <!-- Cases -->

            <li title="My Cases">

                <a href="cases.php">

                    <?php echo icon('briefcase'); ?>

                </a>

            </li>


            <!-- Reports -->

            <li title="Reports">

                <a href="reports.php">

                    <?php echo icon('chart'); ?>

                </a>

            </li>


        </ul>


        <!-- Logout -->

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


        <!-- TOPBAR -->

        <header class="topbar">


            <div>

                <h1>LegalAid</h1>

                <p class="subtitle">
                    Client Profile
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



        <!-- PAGE HEADING -->

        <section class="page-heading">

            <div>

                <h2>My Profile</h2>

                <p>
                    Manage your personal information and account details.
                </p>

            </div>

        </section>



        <!-- MESSAGE -->

        <?php if(!empty($message)): ?>

            <div class="message <?php echo htmlspecialchars($message_type); ?>">

                <?php
                echo htmlspecialchars($message);
                ?>

            </div>

        <?php endif; ?>



        <!-- =================================================
             PROFILE CONTENT
             ================================================= -->

        <section class="profile-layout">


            <!-- PROFILE OVERVIEW -->

            <div class="profile-card">


                <div class="profile-avatar">

                    <?php

                    echo strtoupper(
                        substr(
                            $row["name"] ?: $username,
                            0,
                            1
                        )
                    );

                    ?>

                </div>


                <h3>

                    <?php
                    echo htmlspecialchars(
                        $row["name"]
                    );
                    ?>

                </h3>


                <span class="profile-role">

                    <?php
                    echo htmlspecialchars(
                        ucfirst($row["role"])
                    );
                    ?>

                </span>


                <div class="profile-divider"></div>


                <div class="profile-info">


                    <!-- Email -->

                    <div class="info-item">

                        <div class="info-icon">

                            <?php echo icon('mail'); ?>

                        </div>

                        <div>

                            <span class="info-label">
                                Email
                            </span>

                            <span class="info-value">

                                <?php
                                echo htmlspecialchars(
                                    $row["email"]
                                );
                                ?>

                            </span>

                        </div>

                    </div>


                    <!-- Phone -->

                    <div class="info-item">

                        <div class="info-icon">

                            <?php echo icon('phone'); ?>

                        </div>

                        <div>

                            <span class="info-label">
                                Phone
                            </span>

                            <span class="info-value">

                                <?php
                                echo htmlspecialchars(
                                    $row["phone"]
                                );
                                ?>

                            </span>

                        </div>

                    </div>


                    <!-- Account Type -->

                    <div class="info-item">

                        <div class="info-icon">

                            <?php echo icon('shield'); ?>

                        </div>

                        <div>

                            <span class="info-label">
                                Account Type
                            </span>

                            <span class="info-value">

                                <?php
                                echo htmlspecialchars(
                                    ucfirst($row["role"])
                                );
                                ?>

                            </span>

                        </div>

                    </div>


                </div>


            </div>



            <!-- EDIT PROFILE -->

            <div class="form-card">


                <div class="form-heading">


                    <div class="section-icon">

                        <?php echo icon('edit'); ?>

                    </div>


                    <div>

                        <h3>
                            Edit Profile
                        </h3>

                        <p>
                            Update your contact details below.
                        </p>

                    </div>


                </div>



                <form method="post">


                    <!-- NAME -->

                    <div class="form-group">

                        <label for="name">
                            Full Name
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="<?php
                            echo htmlspecialchars(
                                $row["name"]
                            );
                            ?>"
                            required
                        >

                    </div>



                    <!-- EMAIL -->

                    <div class="form-group">

                        <label for="email">
                            Email Address
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="<?php
                            echo htmlspecialchars(
                                $row["email"]
                            );
                            ?>"
                            required
                        >

                    </div>



                    <!-- PHONE -->

                    <div class="form-group">

                        <label for="phone">
                            Phone Number
                        </label>

                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="<?php
                            echo htmlspecialchars(
                                $row["phone"]
                            );
                            ?>"
                            required
                        >

                    </div>



                    <!-- ROLE -->

                    <div class="form-group">

                        <label for="role">
                            Account Role
                        </label>

                        <input
                            type="text"
                            id="role"
                            value="<?php
                            echo htmlspecialchars(
                                ucfirst($row["role"])
                            );
                            ?>"
                            readonly
                            class="readonly-input"
                        >

                        <span class="field-note">
                            Your account role cannot be changed here.
                        </span>

                    </div>



                    <!-- ACTIONS -->

                    <div class="form-actions">


                        <a href="../../dashboard.php"
                           class="cancel-btn">

                            Back to Dashboard

                        </a>


                        <button
                            type="submit"
                            name="update"
                            class="submit-btn">

                            <?php echo icon('edit'); ?>

                            Update Profile

                        </button>


                    </div>


                </form>


            </div>


        </section>



        <!-- FOOTER -->

        <footer class="footer">

            &copy;
            <?php echo date("Y"); ?>
            LegalAid. All rights reserved.

        </footer>


    </main>

</div>

</body>

</html>