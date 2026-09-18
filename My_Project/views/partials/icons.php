<?php

// Returns inline SVG markup for a named icon. Shared by every page that
// uses the sidebar, so the icon set only needs to be defined once.
function icon($name) {
    $icons = [
        'home' => '<path d="M4 11.5 12 5l8 6.5"/><path d="M6 10v9a1 1 0 0 0 1 1h4v-6h2v6h4a1 1 0 0 0 1-1v-9"/>',
        'user' => '<circle cx="12" cy="8" r="3.5"/><path d="M5 20c1.2-3.6 4-5.5 7-5.5s5.8 1.9 7 5.5"/>',
        'search' => '<circle cx="10.5" cy="10.5" r="6"/><path d="m20 20-4.8-4.8"/>',
        'calendar' => '<rect x="4" y="5.5" width="16" height="14.5" rx="2"/><path d="M4 10h16M8 3.5v3M16 3.5v3"/>',
        'briefcase' => '<rect x="3.5" y="8" width="17" height="11" rx="2"/><path d="M8.5 8V6a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v2"/><path d="M3.5 13h17"/>',
        'chart' => '<path d="M4 20V10M11 20V4M18 20v-7"/><path d="M2.5 20.5h19"/>',
        'logout' => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/>',
        'back' => '<path d="M19 12H5"/><path d="m11 18-6-6 6-6"/>',
    ];
    $body = $icons[$name] ?? '';
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">' . $body . '</svg>';
}