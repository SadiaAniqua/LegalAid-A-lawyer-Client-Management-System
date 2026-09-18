<?php

include("db.php");

$message = "";
$messageType = "";

if (isset($_POST["register"]))
{
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $phone = $_POST["phone"];
    $role = $_POST["role"];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepared statement — prevents SQL injection (the previous version
    // concatenated $_POST values directly into the query string).
    $sql = "INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $hashedPassword, $phone, $role);

    if (mysqli_stmt_execute($stmt))
    {
        $message = "Registration successful. You can now log in.";
        $messageType = "success";
    }
    else
    {
        $message = "Registration failed. That email may already be in use.";
        $messageType = "error";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register — LegalAid</title>
<link rel="icon" href="logo/favicon-32.png">
<link rel="apple-touch-icon" href="logo/apple-touch-icon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="register.css">
</head>

<body>

<header>
  <a class="logo" href="home.php">
    <img src="logo/logo-full.svg" alt="LegalAid" class="logo-image">
  </a>
</header>

<main class="auth-wrap">
  <form class="auth-card" method="post">

    <h2>Create your account</h2>
    <p class="auth-subtitle">Register to get started with LegalAid.</p>

    <?php if ($message): ?>
    <div class="message message-<?php echo $messageType; ?>"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <div class="field">
      <label for="name">Name</label>
      <input type="text" id="name" name="name" required>
    </div>

    <div class="field">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required>
    </div>

    <div class="field">
      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>
    </div>

    <div class="field">
      <label for="phone">Phone</label>
      <input type="text" id="phone" name="phone" required>
    </div>

    <div class="field">
      <label for="role">Role</label>
      <select id="role" name="role">
        <option value="client">Client</option>
        <option value="lawyer">Lawyer</option>
      </select>
    </div>

    <button class="button button-primary" type="submit" name="register">Register</button>

    <p class="auth-footer-text">
      Already have an account? <a href="login.php">Login</a>
    </p>

  </form>
</main>

<footer>
  &copy; <?php echo date("Y"); ?> LegalAid. All rights reserved.
</footer>

</body>
</html>