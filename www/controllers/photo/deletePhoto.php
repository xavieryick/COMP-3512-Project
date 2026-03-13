<?php

require "../database/DatabaseQueries.php";
$queries = new DatabaseQueries();
$imageid = (int) $_POST['imageid'];
$queries->delete($imageid);

session_start();
$photos = [];    
foreach ($queries->returnNonDeleted() as $photo) {
    $photos[] = $photo;
}
$_SESSION['photos'] = $photos;

header("Location: /admin/dashboard/photos");
die();
