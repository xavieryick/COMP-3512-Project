<?php

require '../coretools.php';

session_start();
$errors = [];

require "../database/DatabaseQueries.php";
$queries = new DatabaseQueries();

$username = $_POST["username"]; // gets username
$password = $_POST["password"]; // guess what this does
$remember = isset($_POST["remember"]) ? $_POST["remember"] : "n";

$_SESSION['username'] = $username;

if (strlen($username) == 0) { // checks for blank username
    $errors['loginError'] = "Username cannot be blank!";
    $_SESSION['loginError'] = $errors['loginError'];
    header("Location: /admin");
    die();
} else { // username is not blank
    if ($queries->usernameCheck($username) == 0) { // checks for invalid username
        $errors['loginError'] = "Invalid username or password!";
        $_SESSION['loginError'] = $errors['loginError'];
        header("Location: /admin");
        die();
    } else { // username is valid
        if (strlen($password) == 0) { // checks for blank password
            $errors['loginError'] = "Password cannot be blank!";
            $_SESSION['loginError'] = $errors['loginError'];
            header("Location: /admin");
            die();
        } else { // password is not blank
            $stored = $queries->passwordGrab($username); // grabs associated (hashed) password from username
            if (password_verify($password, $stored)) { // legal password
                $_SESSION['authorized'] = true;
                if ($remember == "y") {
                    $user_id = $queries->getUserID($username); // gets userid 
                    $token = password_hash($password, PASSWORD_BCRYPT); // generates token
                    $queries->insertToken($token, strtotime('+30 days'), $user_id); // inserts into token table
                    setcookie("rememberme", $token, strtotime('+30 days'), "/", "", false, true);
                    $_SESSION['username'] = $username; // sets for session
                } else if ($remember == "n") {
                    $_SESSION['username'] = $username; // sets for session
                }
                header("Location: /admin/dashboard/stats");
                die();
            } else { // illegal password
                $errors['loginError'] = "Invalid username or password!";
                $_SESSION['loginError'] = $errors['loginError'];
                header("Location: /admin");
                die();
            }
        }
    }
}
