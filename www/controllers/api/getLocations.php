<?php

require "../database/DatabaseQueries.php";
$queries = new DatabaseQueries();

$continents = $queries->continents();
$countries = $queries->countries();
$cities = $queries->cities();

$locations = array_merge($continents, $countries, $cities);

header("Content-Type: application/JSON");
echo json_encode($locations);
