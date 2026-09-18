<?php

session_start();

/* =========================================================
   DATABASE
   ========================================================= */

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
   LAWYER CONTROLLER
   ========================================================= */

include("../controllers/LawyerController.php");


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

<title>Find a Lawyer — LegalAid</title>


<!-- Favicon -->

<link rel="icon" href="../logo/favicon-32.png">

<link rel="apple-touch-icon"
      href="../logo/apple-touch-icon.png">


<!-- Google Fonts -->

<link rel="preconnect"
      href="https://fonts.googleapis.com">

<link rel="preconnect"
      href="https://fonts.gstatic.com"
      crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=Inter:wght@400;500;600&display=swap"
      rel="stylesheet">


<!-- CSS -->

<link rel="stylesheet"
      href="findLawyer.css">

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
                    Find a Lawyer
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
                    Find a Lawyer
                </h2>

                <p>
                    Search verified lawyers by name or specialization.
                </p>

            </div>


        </section>



        <!-- =================================================
             SEARCH
             ================================================= -->

        <div class="search-bar">


            <!-- SEARCH FIELD -->

            <div class="search-field">


                <span class="search-icon">

                    <?php
                    echo icon('search');
                    ?>

                </span>


                <input
                    type="text"
                    id="search"
                    placeholder="Search lawyer by name"
                    autocomplete="off"
                >


            </div>



            <!-- SPECIALIZATION -->

            <select id="specialization_id">


                <option value="">
                    All specializations
                </option>


                <?php

                if(isset($specializations) &&
                   $specializations !== false)
                {

                    while(
                        $specialization =
                        mysqli_fetch_assoc($specializations)
                    )
                    {

                ?>

                <option
                    value="<?php
                    echo htmlspecialchars(
                        $specialization["specialization_id"]
                    );
                    ?>"
                >

                    <?php
                    echo htmlspecialchars(
                        $specialization["name"]
                    );
                    ?>

                </option>


                <?php

                    }

                }

                ?>


            </select>


        </div>



        <!-- =================================================
             LAWYER RESULTS
             ================================================= -->

        <section class="results-card">


            <div class="table-wrapper">


                <table>


                    <thead>

                        <tr>

                            <th>
                                Lawyer
                            </th>

                            <th>
                                Specialization
                            </th>

                            <th>
                                Experience
                            </th>

                            <th>
                                Chamber
                            </th>

                            <th>
                                Consultation Fee
                            </th>

                            <th>
                            </th>

                        </tr>

                    </thead>


                    <tbody id="lawyerResult">


                    <?php


                    /*
                     * Initial lawyer list
                     */

                    if(
                        isset($result) &&
                        $result !== false &&
                        mysqli_num_rows($result) > 0
                    ):

                        while(
                            $row =
                            mysqli_fetch_assoc($result)
                        ):

                    ?>


                    <tr>


                        <!-- LAWYER -->

                        <td>

                            <div class="person-cell">

                                <span class="person-avatar">

                                    <?php

                                    echo strtoupper(
                                        substr(
                                            $row["name"],
                                            0,
                                            1
                                        )
                                    );

                                    ?>

                                </span>


                                <span>

                                    <?php

                                    echo htmlspecialchars(
                                        $row["name"]
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



                        <!-- EXPERIENCE -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row["experience"]
                            );

                            ?>

                            years

                        </td>



                        <!-- CHAMBER -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row["chamber_name"]
                            );

                            ?>

                        </td>



                        <!-- CONSULTATION FEE -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row["consultation_fee"]
                            );

                            ?>

                        </td>



                        <!-- ACTION -->

                        <td class="action-cell">


                            <a
                                class="button button-small"
                                href="bookAppointment.php?lawyer_id=<?php

                                echo urlencode(
                                    $row["lawyer_id"]
                                );

                                ?>"
                            >

                                Book Appointment

                            </a>


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

                            No lawyers found.

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



<!-- =========================================================
     AJAX LAWYER SEARCH
     ========================================================= -->

<script>


function searchLawyers()
{

    var search =
        document.getElementById("search").value;


    var specialization =
        document.getElementById(
            "specialization_id"
        ).value;


    var xhr =
        new XMLHttpRequest();


    xhr.onreadystatechange = function()
    {

        if(xhr.readyState === 4)
        {

            if(xhr.status === 200)
            {

                document.getElementById(
                    "lawyerResult"
                ).innerHTML =
                    xhr.responseText;

            }

        }

    };


    xhr.open(
        "POST",
        "../ajax/lawyerSearch.php",
        true
    );


    xhr.setRequestHeader(
        "Content-type",
        "application/x-www-form-urlencoded"
    );


    xhr.send(
        "search=" +
        encodeURIComponent(search) +
        "&specialization_id=" +
        encodeURIComponent(specialization)
    );

}


/* =========================================================
   SEARCH WHILE TYPING
   ========================================================= */

document.getElementById("search")
.addEventListener(
    "keyup",
    searchLawyers
);


/* =========================================================
   SEARCH WHEN SPECIALIZATION CHANGES
   ========================================================= */

document.getElementById("specialization_id")
.addEventListener(
    "change",
    searchLawyers
);


</script>


</body>

</html>