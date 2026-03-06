<?php
/* ========================================
   DESOK LUXURY APARTMENT - LOGOUT HANDLER
   Logs out resident from portal
   ======================================== */

session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Destroy remember me cookie
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/');
}

// Destroy the session
session_destroy();

// Redirect to portal login page
header('Location: ../portal.php');
exit;
?>
