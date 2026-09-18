<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LegalAid</title>
<link rel="icon" href="logo/favicon-32.png">
<link rel="apple-touch-icon" href="logo/apple-touch-icon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="home.css">
</head>

<body>

<header>
  <a class="logo" href="home.php">
    <img src="logo/logo-full.svg" alt="LegalAid" class="logo-image">
  </a>
</header>

<section class="hero">
  <div class="hero-content">
    <h2>Legal help, made accessible.</h2>
    <p>
      Connect with verified lawyers, manage your cases, and get the support
      you need — all in one place.
    </p>
    <div class="hero-actions">
      <a class="button button-primary" href="login.php">Login</a>
      <a class="button button-secondary" href="register.php">Register</a>
    </div>
  </div>
</section>

<footer>
  &copy; <?php echo date("Y"); ?> LegalAid. All rights reserved.
</footer>

</body>
</html>