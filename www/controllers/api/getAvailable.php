<?php

require "../database/DatabaseQueries.php";
$queries = new DatabaseQueries();

$availableCities = $queries->countAvailableCities();
$availableCountries = $queries->countAvailableCountries();
$availableContinents = $queries->countAvailableContinents();
$availableCounts = array_merge($availableContinents, $availableCountries, $availableCities);

header("Content-Type: application/JSON");
echo json_encode($availableCounts);
