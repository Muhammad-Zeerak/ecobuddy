<?php
// website configuration variables
define('SITE_NAME', 'ecoBuddy');
define('SITE_URL', '/ecobuddy');
define('DB_PATH', __DIR__ . '/../database/ecobuddy.sqlite');

// Session settings
session_start();

// Error settings
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Helper functions
function redirect($location) {
    header("Location: " . SITE_URL . $location);
    exit;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isManager() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] == 1;
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}