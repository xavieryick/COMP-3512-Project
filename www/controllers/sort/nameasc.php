<?php

session_start();

function compare($photoA, $photoB) {
    $fullnameA = $photoA["FirstName"] . $photoA["LastName"];
    $fullnameB = $photoB["FirstName"] . $photoB["LastName"];
    return strcmp($fullnameA, $fullnameB);
}

$array = $_SESSION['photos'];
usort($array, "compare");
$_SESSION['photos'] = $array;
$_SESSION['pointer'] = 0;

header("Location: /admin/dashboard/photos");
die();
