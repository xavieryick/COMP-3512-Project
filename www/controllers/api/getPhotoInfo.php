<?php

require "../database/DatabaseQueries.php";
$queries = new DatabaseQueries();

$details = $queries->getPhotoInfo();

header("Content-Type: application/JSON");
echo json_encode($details);
