<?php
/**
 * Admin Logout
 * Greendome Engineering Ticketing System
 */

session_name('GD_ADMIN_SESSION');
session_start();

// Destroy session
$_SESSION = [];
session_destroy();

// Redirect to login
header("Location: login.php");
exit;
