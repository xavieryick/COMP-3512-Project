<?php

require "../database/DatabaseQueries.php";
$queries = new DatabaseQueries();

$details = $queries->getCountryInfo();

foreach ($details as &$country) {
    // grabbing
    $languages = $country["Languages"];
    $neighbours = $country["Neighbours"];
    $population = $country["Population"];

    // split non-null
    if ($languages !== null) {
        $explodedLang = explode(",", $languages);
        foreach ($explodedLang as &$index) {
            if (str_contains($index, "-")) {
                $usable = explode('-', $index)[0];
                $language = $queries->getCountryLanguages($usable);
                if (isset($language["name"]) && $language["name"] !== false) $index = $language["name"];
                else $index = null;
            } else {
                $language = $queries->getCountryLanguages($index);
                if (isset($language["name"]) && $language["name"] !== false) $index = $language["name"];
                else $index = null;
            }
        }
        $explodedLang = array_filter($explodedLang, function ($value) {
            return $value !== null && $value !== false;
        });

        $explodedLang = array_values($explodedLang);
        $languages = implode(", ", $explodedLang);
        $country["Languages"] = $languages;
    }

    if ($neighbours !== null) {
        $explodedNeigh = explode(",", $neighbours);
        foreach ($explodedNeigh as &$index) {
            if (isset($index) && $index !== "") {
                $neighbour = $queries->getCountryNeighbours($index);
                if (isset($neighbour["CountryName"])) $index = $neighbour["CountryName"];
                else $index = null;
            } else $index = null;
        }

        $explodedNeigh = array_filter($explodedNeigh, function ($value) {
            return $value !== null && $value !== false && $value !== "";
        });

        $explodedNeigh = array_values($explodedNeigh);
        $neighbours = implode(", ", $explodedNeigh);
        $country["Neighbours"] = $neighbours;
    }

    $population_formatted = number_format($population);
    $country["Population"] = $population_formatted;
}

header("Content-Type: application/JSON");
echo json_encode($details);
