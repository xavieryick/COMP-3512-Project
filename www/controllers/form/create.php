<?php

session_start();
require "../database/DatabaseQueries.php";
$queries = new DatabaseQueries();

if (isset($_COOKIE['rememberme'])) {
    $token = $_COOKIE['rememberme'];
    $user_id = $queries->getUserIDFromToken($token);
    $username = $queries->getUsernameFromID($user_id);
    $_SESSION['username'] = $username;
    $_SESSION['authorized'] = true;
    header("Location: /admin/dashboard/stats");
    die();
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$checked = isset($_SESSION['remember']) ? 'checked' : '';

require "../views/login.view.php";
