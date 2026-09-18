<?php

// Shared sidebar for client pages.
// The including page must set $activePage (e.g. 'cases') before including this file.

include(__DIR__ . "/icons.php");

$navItems = [
    ['key' => 'profile',      'icon' => 'user',      'title' => 'Edit Profile',    'href' => 'profile.php'],
    ['key' => 'findLawyer',   'icon' => 'search',    'title' => 'Find Lawyer',     'href' => 'findLawyer.php'],
    ['key' => 'appointments', 'icon' => 'calendar',  'title' => 'My Appointments', 'href' => 'appointments.php'],
    ['key' => 'cases',        'icon' => 'briefcase', 'title' => 'My Cases',        'href' => 'cases.php'],
    ['key' => 'reports',      'icon' => 'chart',     'title' => 'Reports',         'href' => 'reports.php'],
];

$activePage = $activePage ?? '';

?>
<nav class="sidebar">
  <a class="sidebar-logo" href="../../dashboard.php">
    <img src="../../logo/logo-icon.svg" alt="LegalAid">
  </a>

  <ul class="sidebar-nav">
    <li title="Dashboard"><a href="../../dashboard.php"><?php echo icon('home'); ?></a></li>
    <?php foreach ($navItems as $item): ?>
    <li class="<?php echo ($activePage === $item['key']) ? 'active' : ''; ?>" title="<?php echo htmlspecialchars($item['title']); ?>">
      <a href="<?php echo htmlspecialchars($item['href']); ?>"><?php echo icon($item['icon']); ?></a>
    </li>
    <?php endforeach; ?>
  </ul>

  <div class="sidebar-bottom">
    <a href="../../logout.php" title="Logout"><?php echo icon('logout'); ?></a>
  </div>
</nav>