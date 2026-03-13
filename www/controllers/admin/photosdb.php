<?php

require '../database/DatabaseQueries.php';
$queries = new DatabaseQueries();

if (isset($_SESSION["photos"]) && isset($_SESSION["pointer"])) { // photos sorted, index already chosen
    $photos = $_SESSION['photos'];
    $pointer = $_SESSION['pointer'];
} else if (isset($_SESSION["photos"])) { // photos sorted, index not chosen
    $photos = $_SESSION['photos'];
    $pointer = 0;
    function compare($photoA, $photoB) { // used to sort by id ascending
        return $photoA["ImageID"] - $photoB["ImageID"];
    }
    usort($photos, "compare"); // sorts array
} else {
    $photos = []; // creates array
    foreach ($queries->returnNonDeleted() as $photo) { //adds all available
        $photos[] = $photo;
    }
    $pointer = 0; // sets pointer to first item
    function compare($photoA, $photoB) { // used to sort by id ascending
        return $photoA["ImageID"] - $photoB["ImageID"];
    }
    usort($photos, "compare"); // sorts array
}

$_SESSION['photos'] = $photos;
$_SESSION['pointer'] = $pointer;

$target_image_id = $photos[$pointer]["ImageID"];

foreach ($photos as $photo) {
    if ((int)$target_image_id === $photo['ImageID']) {
        $target_photo = $queries->getPhotoDetails($target_image_id);

        $max = end($photos)["ImageID"];

        $flagged = $queries->flaggedCheck($target_image_id);
        if ($flagged > 0) $target_photo['flagged'] = "Yes";
        else $target_photo['flagged'] = "No";

        function findURL($target_photo) {
            $filename = $target_photo['Path'];
            $url = "https://res.cloudinary.com/dg0durrxj/image/upload/w_250,h_250,c_fill/$filename";
            return $url;
        }

        require '../views/photosdb.view.php';
        die();
    }
}
