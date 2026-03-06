<?php
/**
 * Logout
 * Greendome Engineering Technology & Science
 */

session_start();
session_unset();
session_destroy();

header("Location: login.php");
exit;
