<?php

session_start();

function compare($photoA, $photoB) {
    return strcmp($photoB["Title"], $photoA["Title"]);
}

$array = $_SESSION['photos'];
usort($array, "compare");
$_SESSION['photos'] = $array;
$_SESSION['pointer'] = 0;

header("Location: /admin/dashboard/photos");
die();
