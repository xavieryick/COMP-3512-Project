<?php

require "../database/DatabaseQueries.php";
$queries = new DatabaseQueries();

$totalCities = $queries->countTotalCities();
$totalCountries = $queries->countTotalCountries();
$totalContinents = $queries->countTotalContinents();
$totalCounts = array_merge($totalContinents, $totalCountries, $totalCities);

header("Content-Type: application/JSON");
echo json_encode($totalCounts);
