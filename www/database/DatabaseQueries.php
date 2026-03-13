<?php

require 'DatabaseConnection.php';

class DatabaseQueries {
    private $db_connection;

    public function __construct() {
        $config = require 'config.php';
        $this->db_connection = new DatabaseConnection($config);
    }

    public function __destruct() {
        $this->disconnect();
    }

    public function disconnect() {
        $this->db_connection->close_connection();
    }

    // returns imageid, title, first name, last name, and path from imagedetails using imageid
    public function getPhotoDetails($imageid) {
        $query = <<<QUERY
            SELECT id.ImageID, id.Title, u.FirstName, u.LastName, id.Path, cities.AsciiName, countries.CountryName, continents.ContinentName
            FROM imagedetails id
            JOIN users u ON id.UserID = u.UserID
            JOIN cities ON id.CityCode = cities.CityCode
            JOIN countries ON id.CountryCodeISO = countries.ISO
            JOIN continents ON id.ContinentCode = continents.ContinentCode
            WHERE ImageID = :imageid;
        QUERY;
        return $this->db_connection->run($query, [':imageid' => $imageid])->fetch();
    }

    // returns number of photos in imagedetails
    public function totalPhotos() {
        $query = <<<QUERY
            SELECT COUNT(id.ImageID) 
            FROM imagedetails id
            LEFT JOIN deleted d ON id.ImageID = d.imageid
            WHERE d.imageid IS NULL
        QUERY;
        return $this->db_connection->run($query)->fetch();
    }

    // returns city with most occurances in imagedetails
    public function popularCity() {
        $query = <<<QUERY
            SELECT id.CityCode, COUNT(id.CityCode), c.AsciiName 
            FROM imagedetails id 
            JOIN cities c ON id.CityCode = c.CityCode 
            LEFT JOIN deleted d ON id.ImageID = d.imageid
            WHERE d.imageid IS NULL
            GROUP BY CityCode 
            ORDER BY COUNT(CityCode) DESC;
        QUERY;
        return $this->db_connection->run($query)->fetch();
    }

    // inserts photo to flagged using imageid
    public function flag($imageid) {
        $query = <<<QUERY
            INSERT INTO flagged (imageid) VALUES (:imageid);
        QUERY;
        $this->db_connection->run($query, [':imageid' => $imageid]);
    }

    public function unflag($imageid) {
        $query = <<<QUERY
            DELETE FROM flagged WHERE imageid = :imageid;
        QUERY;
        $this->db_connection->run($query, [':imageid' => $imageid]);
    }

    // inserts photo to deleted using imageid
    public function delete($imageid) {
        $query = <<<QUERY
            INSERT INTO deleted (imageid) VALUES (:imageid);
        QUERY;
        $this->db_connection->run($query, [':imageid' => $imageid]);
    }

    // returns 0 or 1 if username is in admin using username
    public function usernameCheck($username) {
        $query = <<<QUERY
            SELECT COUNT(*) FROM administrator WHERE username = :username;
        QUERY;
        return $this->db_connection->run($query, [':username' => $username])->fetchColumn();
    }

    // returns password from admin using username
    public function passwordGrab($username) {
        $query = <<<QUERY
            SELECT password FROM administrator WHERE username = :username;
        QUERY;
        return $this->db_connection->run($query, [':username' => $username])->fetchColumn();
    }

    // returns 0 or 1 if imageid is in flagged using imageid
    public function flaggedCheck($imageid) {
        $query = <<<QUERY
            SELECT COUNT(*) FROM flagged WHERE imageid = :imageid;
        QUERY;
        return $this->db_connection->run($query, [':imageid' => $imageid])->fetchColumn();
    }

    // returns 0 or 1 if imageid is in deleted using imageid
    public function deletedCheck($imageid) {
        $query = <<<QUERY
            SELECT COUNT(*) FROM deleted WHERE imageid = :imageid;
        QUERY;
        return $this->db_connection->run($query, [':imageid' => $imageid])->fetchColumn();
    }

    // returns users in flagged 
    public function flaggedUsers() {
        $query = <<<QUERY
            SELECT u.FirstName, u.LastName, GROUP_CONCAT(DISTINCT f.imageid ORDER BY f.imageid) AS ImageIDs
            FROM flagged AS f
            JOIN imagedetails AS id ON f.imageid = id.ImageID
            JOIN users AS u ON id.UserID = u.UserID
            GROUP BY u.FirstName, u.LastName;
        QUERY;
        return $this->db_connection->run($query)->fetchAll();
    }

    // returns userid from admin using username
    public function getUserID($username) {
        $query = <<<QUERY
            SELECT id FROM administrator WHERE username = :username
        QUERY;
        return $this->db_connection->run($query, [':username' => $username])->fetchColumn();
    }

    public function insertToken($token, $expiry, $user_id) {
        $query = <<<QUERY
            INSERT INTO user_tokens (token, expiry, user_id) VALUES (:token, :expiry, :user_id)
        QUERY;
        return $this->db_connection->run($query, [':token' => $token, ':expiry' => $expiry, ':user_id' => $user_id]);
    }

    public function getUserIDFromToken($token) {
        $query = <<<QUERY
            SELECT user_id FROM user_tokens WHERE token = :token
        QUERY;
        return $this->db_connection->run($query, [':token' => $token])->fetchColumn();
    }

    public function getUsernameFromID($user_id) {
        $query = <<<QUERY
            SELECT username FROM administrator WHERE id = :user_id
        QUERY;
        return $this->db_connection->run($query, [':user_id' => $user_id])->fetchColumn();
    }

    public function generateToken() {
        $selector = bin2hex(random_bytes(16));
        $validator = bin2hex(random_bytes(32));
        return [$selector, $validator, $selector . ':' . $validator];
    }

    public function returnNonDeleted() {
        $query = <<<QUERY
            SELECT id.ImageID, id.Title, u.FirstName, u.LastName, id.Path, cities.AsciiName, countries.CountryName, continents.ContinentName
            FROM imagedetails id
            JOIN users u ON id.UserID = u.UserID
            JOIN cities ON id.CityCode = cities.CityCode
            JOIN countries ON id.CountryCodeISO = countries.ISO
            JOIN continents ON id.ContinentCode = continents.ContinentCode
            LEFT JOIN deleted d ON id.ImageID = d.imageid
            WHERE d.imageid IS NULL
        QUERY;
        return $this->db_connection->run($query)->fetchAll();
    }

    public function continents() {
        $query = <<<QUERY
            SELECT c.ContinentName AS name, COUNT(id.ImageID) AS count
            FROM continents c
            JOIN imagedetails id ON c.ContinentCode = id.ContinentCode
            LEFT JOIN deleted d ON id.ImageID = d.imageid
            WHERE d.imageid IS NULL
            GROUP BY c.ContinentName
        QUERY;
        return $this->db_connection->run($query)->fetchAll();
    }

    public function countries() {
        $query = <<<QUERY
            SELECT c.CountryName AS name, COUNT(id.ImageID) AS count
            FROM countries c
            JOIN imagedetails id ON c.ISO = id.CountryCodeISO
            LEFT JOIN deleted d ON id.ImageID = d.imageid
            WHERE d.imageid IS NULL
            GROUP BY c.CountryName
        QUERY;
        return $this->db_connection->run($query)->fetchAll();
    }

    public function cities() {
        $query = <<<QUERY
            SELECT c.AsciiName AS name, COUNT(id.ImageID) AS count
            FROM cities c
            JOIN imagedetails id ON c.CityCode = id.CityCode
            LEFT JOIN deleted d ON id.ImageID = d.imageid
            WHERE d.imageid IS NULL
            GROUP BY c.AsciiName
        QUERY;
        return $this->db_connection->run($query)->fetchAll();
    }

    public function getMatches() {
        $query = <<<QUERY
            SELECT id.ImageID, u.FirstName, u.LastName, id.Path, cities.AsciiName, countries.CountryName, continents.ContinentName, id.CountryCodeISO
            FROM imagedetails id
            JOIN users u ON id.UserID = u.UserID
            JOIN cities ON id.CityCode = cities.CityCode
            JOIN countries ON id.CountryCodeISO = countries.ISO
            JOIN continents ON id.ContinentCode = continents.ContinentCode
            LEFT JOIN deleted d ON id.ImageID = d.imageid
            WHERE d.imageid IS NULL
        QUERY;
        return $this->db_connection->run($query)->fetchAll();
    }

    public function getFlagged() {
        $query = <<<QUERY
            SELECT imageid
            FROM flagged
        QUERY;
        return $this->db_connection->run($query)->fetchAll();
    }

    // can use these right away
    public function getCountryInfo() {
        $query = <<<QUERY
            SELECT c.CountryName, c.Population, c.Capital, c.CurrencyName, c.CurrencyCode, c.Languages, c.Neighbours, c.CountryDescription, c.ISO
            FROM countries AS c
        QUERY;
        return $this->db_connection->run($query)->fetchAll();
    }

    // will convert language iso to language name
    public function getCountryLanguages($iso) {
        $query = <<<QUERY
            SELECT name
            FROM languages
            WHERE iso = :iso
        QUERY;
        return $this->db_connection->run($query, [':iso' => $iso])->fetch();
    }

    // will convert neighbour iso to neighbour name
    public function getCountryNeighbours($iso) {
        $query = <<<QUERY
            SELECT CountryName
            FROM countries
            WHERE ISO = :iso
        QUERY;
        return $this->db_connection->run($query, [':iso' => $iso])->fetch();
    }

    public function getPhotoInfo() {
        $query = <<<QUERY
            SELECT id.ImageID, id.Path, id.Description, id.Exif, id.ActualCreator, c.AsciiName, id.Latitude, id.Longitude, AVG(ir.Rating) AS averageRating
            FROM imagedetails AS id
            JOIN cities AS c ON id.CityCode = c.CityCode
            JOIN imagerating AS ir ON id.ImageID = ir.ImageID
            GROUP BY id.ImageID, id.Path, id.Description, id.Exif, id.ActualCreator, c.AsciiName, id.Latitude, id.Longitude
        QUERY;
        return $this->db_connection->run($query)->fetchAll();
    }


    // city counts
    public function countTotalCities() {
        $query = <<<QUERY
            SELECT c.AsciiName AS location, COUNT(id.ImageID) AS count
            FROM imagedetails AS id
            JOIN cities AS c
            ON id.CityCode = c.CityCode
            GROUP BY AsciiName
        QUERY;
        return $this->db_connection->run($query)->fetchAll();
    }

    public function countAvailableCities() {
        $query = <<<QUERY
            SELECT c.AsciiName AS location, COUNT(id.ImageID) AS count
            FROM imagedetails AS id
            JOIN cities AS c
            ON id.CityCode = c.CityCode
            LEFT JOIN deleted AS d ON id.ImageID = d.imageid
            WHERE d.imageid IS NULL
            GROUP BY AsciiName
        QUERY;
        return $this->db_connection->run($query)->fetchAll();
    }

    // country counts
    public function countTotalCountries() {
        $query = <<<QUERY
            SELECT c.CountryName AS location, COUNT(id.ImageID) AS count
            FROM imagedetails AS id
            JOIN countries AS c
            ON id.CountryCodeISO = c.ISO
            GROUP BY CountryName
        QUERY;
        return $this->db_connection->run($query)->fetchAll();
    }

    public function countAvailableCountries() {
        $query = <<<QUERY
            SELECT c.CountryName AS location, COUNT(id.ImageID) AS count
            FROM imagedetails AS id
            JOIN countries AS c
            ON id.CountryCodeISO = c.ISO
            LEFT JOIN deleted AS d ON id.ImageID = d.imageid
            WHERE d.imageid IS NULL
            GROUP BY CountryName
        QUERY;
        return $this->db_connection->run($query)->fetchAll();
    }

    // continent counts
    public function countTotalContinents() {
        $query = <<<QUERY
            SELECT c.ContinentName AS location, COUNT(id.ImageID) AS count
            FROM imagedetails AS id
            JOIN continents AS c
            ON id.ContinentCode = c.ContinentCode
            GROUP BY ContinentName
        QUERY;
        return $this->db_connection->run($query)->fetchAll();
    }

    public function countAvailableContinents() {
        $query = <<<QUERY
            SELECT c.ContinentName AS location, COUNT(id.ImageID) AS count
            FROM imagedetails AS id
            JOIN continents AS c
            ON id.ContinentCode = c.ContinentCode
            LEFT JOIN deleted AS d ON id.ImageID = d.imageid
            WHERE d.imageid IS NULL
            GROUP BY ContinentName
        QUERY;
        return $this->db_connection->run($query)->fetchAll();
    }
}
