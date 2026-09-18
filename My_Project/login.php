<?php

session_start();

include("db.php");

$message = "";

if (isset($_POST["login"]))
{
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Prepared statement — prevents SQL injection (the previous version
    // concatenated $_POST["email"] directly into the query string).
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0)
    {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row["password"]))
        {
            if ($row["status"] == "banned")
            {
                $message = "Your account has been banned by the administrator.";
            }
            else
            {
                $_SESSION["id"] = $row["user_id"];
                $_SESSION["username"] = $row["name"];
                $_SESSION["role"] = $row["role"];

                header("Location: dashboard.php");
                exit();
            }
        }
        else
        {
            $message = "Wrong password.";
        }
    }
    else
    {
        $message = "Email not found.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — LegalAid</title>
<link rel="icon" href="logo/favicon-32.png">
<link rel="apple-touch-icon" href="logo/apple-touch-icon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="login.css">
</head>

<body>

<header>
  <a class="logo" href="home.php">
    <img src="logo/logo-full.svg" alt="LegalAid" class="logo-image">
  </a>
</header>

<main class="auth-wrap">
  <form class="auth-card" method="post">

    <h2>Welcome back</h2>
    <p class="auth-subtitle">Log in to your LegalAid account.</p>

    <?php if ($message): ?>
    <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <div class="field">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required>
    </div>

    <div class="field">
      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>
    </div>

    <button class="button button-primary" type="submit" name="login">Login</button>

    <p class="auth-footer-text">
      Don't have an account? <a href="register.php">Register</a>
    </p>

  </form>
</main>

<footer>
  &copy; <?php echo date("Y"); ?> LegalAid. All rights reserved.
</footer>

</body>
</html>