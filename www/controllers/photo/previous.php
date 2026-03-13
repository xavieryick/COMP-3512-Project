<?php

session_start();

$pointer = $_SESSION['pointer'];
$photos = $_SESSION['photos'];

foreach ($photos as $photo) {
    if ($photos[$pointer]["ImageID"] === $photo["ImageID"]) {
        $pointer = $pointer - 1;
        $_SESSION["pointer"] = $pointer;
        break;
    }
}

header("Location: /admin/dashboard/photos");
die();
