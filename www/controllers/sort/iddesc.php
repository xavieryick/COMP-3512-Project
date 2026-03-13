<?php

session_start();

function compare($photoA, $photoB) {
    return $photoB["ImageID"] - $photoA["ImageID"];
}

$array = $_SESSION['photos'];
usort($array, "compare");
$_SESSION['photos'] = $array;
$_SESSION['pointer'] = 0;

header("Location: /admin/dashboard/photos");
die();
