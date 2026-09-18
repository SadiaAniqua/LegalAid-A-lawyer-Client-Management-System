<?php

session_start();

if(!isset($_SESSION["id"]))
{
    header("Location: login.php");
    exit;
}

$role = $_SESSION["role"];
$username = $_SESSION["username"];

// ---- Icon library (inline SVG, stroke-based to match the sidebar style) ----
function icon($name) {
    $icons = [
        'home' => '<path d="M4 11.5 12 5l8 6.5"/><path d="M6 10v9a1 1 0 0 0 1 1h4v-6h2v6h4a1 1 0 0 0 1-1v-9"/>',
        'user' => '<circle cx="12" cy="8" r="3.5"/><path d="M5 20c1.2-3.6 4-5.5 7-5.5s5.8 1.9 7 5.5"/>',
        'search' => '<circle cx="10.5" cy="10.5" r="6"/><path d="m20 20-4.8-4.8"/>',
        'calendar' => '<rect x="4" y="5.5" width="16" height="14.5" rx="2"/><path d="M4 10h16M8 3.5v3M16 3.5v3"/>',
        'briefcase' => '<rect x="3.5" y="8" width="17" height="11" rx="2"/><path d="M8.5 8V6a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v2"/><path d="M3.5 13h17"/>',
        'chart' => '<path d="M4 20V10M11 20V4M18 20v-7"/><path d="M2.5 20.5h19"/>',
        'users' => '<circle cx="9" cy="8" r="3"/><path d="M2.5 20c1-3.3 3.4-5 6.5-5s5.5 1.7 6.5 5"/><circle cx="17" cy="9" r="2.4"/><path d="M15.8 12.2c2.3.3 3.9 1.8 4.7 4.3"/>',
        'shield' => '<path d="M12 3.5 19 6v6c0 4.4-3 7.8-7 8.5-4-.7-7-4.1-7-8.5V6z"/><path d="m9 12 2 2 4-4.2"/>',
        'folder' => '<path d="M3.5 7.5a1.5 1.5 0 0 1 1.5-1.5h4l2 2.2h8a1.5 1.5 0 0 1 1.5 1.5V18a1.5 1.5 0 0 1-1.5 1.5H5A1.5 1.5 0 0 1 3.5 18z"/>',
        'logout' => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/>',
    ];
    $body = $icons[$name] ?? '';
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">' . $body . '</svg>';
}

// ---- Nav + card content per role ----
$decks = [
    'client' => [
        'label' => 'Client',
        'items' => [
            ['icon' => 'user',     'title' => 'Edit Profile',    'desc' => 'Update your contact details and account info.', 'href' => 'client/profile.php'],
            ['icon' => 'search',   'title' => 'Find Lawyer',     'desc' => 'Search verified lawyers by practice area.',      'href' => 'client/findLawyer.php'],
            ['icon' => 'calendar', 'title' => 'My Appointments', 'desc' => 'Review upcoming and past consultations.',        'href' => 'views/client/appointments.php'],
            ['icon' => 'briefcase','title' => 'My Cases',        'desc' => 'Track the status of your active cases.',         'href' => 'views/client/cases.php'],
            ['icon' => 'chart',    'title' => 'Reports',         'desc' => 'Download a summary of your case activity.',      'href' => 'client/reports.php'],
        ],
    ],
    'lawyer' => [
        'label' => 'Lawyer',
        'items' => [
            ['icon' => 'user',     'title' => 'My Profile',      'desc' => 'Manage your public profile and credentials.',    'href' => 'views/lawyer/profile.php'],
            ['icon' => 'calendar', 'title' => 'Client Bookings',  'desc' => 'View and manage incoming appointment requests.', 'href' => 'views/lawyer/appointments.php'],
            ['icon' => 'briefcase','title' => 'My Cases',        'desc' => 'Manage the cases assigned to you.',              'href' => 'lawyer/cases.php'],
            ['icon' => 'chart',    'title' => 'Reports',         'desc' => 'Download a summary of your case activity.',      'href' => 'lawyer/reports.php'],
        ],
    ],
    'admin' => [
        'label' => 'Admin',
        'items' => [
            ['icon' => 'users',    'title' => 'View Clients',        'desc' => 'Browse and manage registered clients.',       'href' => 'admin/clients.php'],
            ['icon' => 'briefcase','title' => 'View Lawyers',        'desc' => 'Browse and manage registered lawyers.',       'href' => 'admin/lawyers.php'],
            ['icon' => 'shield',   'title' => 'Lawyer Verification', 'desc' => 'Review and approve pending lawyer accounts.', 'href' => 'admin/verification.php'],
            ['icon' => 'calendar', 'title' => 'View Appointments',   'desc' => 'See all appointments across the platform.',   'href' => 'admin/appointments.php'],
            ['icon' => 'folder',   'title' => 'View Cases',          'desc' => 'Browse all cases across the platform.',       'href' => 'admin/cases.php'],
            ['icon' => 'chart',    'title' => 'View Reports',        'desc' => 'Access platform-wide activity reports.',      'href' => 'admin/reports.php'],
        ],
    ],
];

$deck = $decks[$role] ?? null;

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard — LegalAid</title>
<link rel="icon" href="logo/favicon-32.png">
<link rel="apple-touch-icon" href="logo/apple-touch-icon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="dashboard.css">
</head>
<body>

<div class="app">

  <!-- Sidebar -->
  <nav class="sidebar">
    <a class="sidebar-logo" href="dashboard.php">
      <img src="logo/logo-icon.svg" alt="LegalAid">
    </a>

    <ul class="sidebar-nav">
      <li class="active" title="Dashboard"><a href="dashboard.php"><?php echo icon('home'); ?></a></li>
      <?php if ($deck): foreach ($deck['items'] as $item): ?>
      <li title="<?php echo htmlspecialchars($item['title']); ?>">
        <a href="<?php echo htmlspecialchars($item['href']); ?>"><?php echo icon($item['icon']); ?></a>
      </li>
      <?php endforeach; endif; ?>
    </ul>

    <div class="sidebar-bottom">
      <a href="logout.php" title="Logout"><?php echo icon('logout'); ?></a>
    </div>
  </nav>

  <!-- Main content -->
  <main class="main">

    <header class="topbar">
      <div>
        <h1>LegalAid</h1>
        <p class="subtitle">
          <?php echo $deck ? htmlspecialchars($deck['label']) : 'User'; ?> Dashboard
        </p>
      </div>
      <div class="account">
        <span class="account-name"><?php echo htmlspecialchars($username); ?></span>
        <span class="account-role"><?php echo htmlspecialchars(ucfirst($role)); ?></span>
        <div class="avatar"><?php echo strtoupper(substr($username, 0, 1)); ?></div>
      </div>
    </header>

    <section class="greeting">
      <h2>Good morning, <?php echo htmlspecialchars($username); ?>!</h2>
      <p>Here's what you can do from your dashboard today.</p>
    </section>

    <section class="card-grid">
      <?php if ($deck): foreach ($deck['items'] as $item): ?>
      <a class="card" href="<?php echo htmlspecialchars($item['href']); ?>">
        <div class="card-icon"><?php echo icon($item['icon']); ?></div>
        <div class="card-body">
          <h3><?php echo htmlspecialchars($item['title']); ?></h3>
          <p><?php echo htmlspecialchars($item['desc']); ?></p>
        </div>
        <div class="card-arrow">&rarr;</div>
      </a>
      <?php endforeach; else: ?>
      <p>No dashboard is configured for this account role.</p>
      <?php endif; ?>
    </section>

    <footer class="footer">
      &copy; <?php echo date("Y"); ?> LegalAid. All rights reserved.
    </footer>

  </main>
</div>

</body>
</html>