<?php

require '../database/DatabaseQueries.php';
$queries = new DatabaseQueries();

$totalPhotos = $queries->totalPhotos();
$popularCity = $queries->popularCity();
$flaggedUsers = $queries->flaggedUsers();
$flaggedUsersList = [];
foreach ($flaggedUsers as $user) {
    $flaggedUsersList[] = $user["FirstName"] . " " . $user["LastName"] . " (Image ID: " . $user["ImageIDs"] . ")";
}
$flaggedUserCount = count($flaggedUsersList);

require '../views/statsdb.view.php';
