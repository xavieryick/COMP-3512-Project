<?php

require "../database/DatabaseQueries.php";
$queries = new DatabaseQueries();

$photos = $queries->getMatches();

header("Content-Type: application/JSON");
echo json_encode($photos);
