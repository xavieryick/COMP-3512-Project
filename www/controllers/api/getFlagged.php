<?php

require "../database/DatabaseQueries.php";
$queries = new DatabaseQueries();

$flagged = $queries->getFlagged();

header("Content-Type: application/JSON");
echo json_encode($flagged);
