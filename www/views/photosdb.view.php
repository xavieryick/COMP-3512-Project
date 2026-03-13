<?php
$array = $_SESSION['photos'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Dashboard</title>
    <link rel="stylesheet" href="/styles/reset.css">
    <link rel="stylesheet" href="/styles/styles.css">
</head>

<body class="container">
    <?php require "partials/header.partial.php" ?>
    <main class="container centered">
        <div class="middle">
            <div class="photo">
                <?php $url = findURL($target_photo) ?>
                <img src=<?= $url ?> alt=<?= $target_photo['ImageID'] ?>>
            </div>
            <div class="description">
                <p>
                    Photo ID: <?= $target_photo['ImageID'] ?> <br>
                    Photo Title: <?= $target_photo['Title'] ?> <br>
                    User: <?= $target_photo['FirstName'] . " " . $target_photo['LastName'] ?> <br>
                    Location: <?= $target_photo['AsciiName'] . ", " . $target_photo['CountryName'] . ", " . $target_photo['ContinentName'] ?> <br>
                    Flagged: <?= $target_photo['flagged'] ?> <br>
                </p>
            </div>
            <div class="buttons">
                <p>Options: </p>
                <?php
                if ($target_photo['flagged'] === "No") require "partials/photosdb/flagbutton.php";
                else if ($target_photo['flagged'] === "Yes") require "partials/photosdb/unflagbutton.php";
                else echo "something wrong lol";
                ?>
                <?php if ($_SESSION["pointer"] > 9) require "partials/photosdb/previous10button.php" ?>
                <?php if ($_SESSION["pointer"] > 0) require "partials/photosdb/previousbutton.php" ?>
                <?php if ($_SESSION["pointer"] < count($_SESSION['photos']) - 1) require "partials/photosdb/nextbutton.php" ?>
                <?php if ($_SESSION["pointer"] < count($_SESSION['photos']) - 10) require "partials/photosdb/next10button.php" ?>
            </div>
            <div class="sortingButtons centered">
                <ul>
                    <li>Sort by:</li>
                    <li>Photo ID: <a href="photos/idasc">Ascending</a> <a href="photos/iddesc">Descending</a></li>
                    <li>Photo Title: <a href="photos/titleasc">Ascending</a> <a href="photos/titledesc">Descending</a></li>
                    <li>Creator Name: <a href="photos/nameasc">Ascending</a> <a href="photos/namedesc">Descending</a></li>
                </ul>
            </div>
        </div>
    </main>
    <?php require "partials/footer.partial.php" ?>
</body>

</html>