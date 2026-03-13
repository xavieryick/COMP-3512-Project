<?php
// set all to hidden
$display;
$scriptsrc;
// determine which to unhide, keeps others hidden
if (str_contains($_SERVER['REQUEST_URI'], '/matches')) {
    $display = 'partials/search/matches.partial.php';
    $scriptsrc = '/scripts/matches.js'; // sets script to matches
} elseif (str_contains($_SERVER['REQUEST_URI'], '/photo')) {
    $display = 'partials/search/photo.partial.php';
    $scriptsrc = '/scripts/pdetails.js'; // sets script to pdetails
} elseif (str_contains($_SERVER['REQUEST_URI'], '/details')) {
    $display = 'partials/search/details.partial.php';
    $scriptsrc = '/scripts/cdetails.js'; // sets script to cdetails
} else {
    $display = 'partials/search/search.partial.php';
    $scriptsrc = '/scripts/search.js'; // sets script to search
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link rel="stylesheet" href="/styles/reset.css">
    <link rel="stylesheet" href="/styles/styles.css">
    <script type="module" src=<?= $scriptsrc ?>></script>
</head>

<body class="container">
    <?php require "partials/header.partial.php" ?>
    <main class="container centered">
        <?php require $display ?>
    </main>
    <?php require "partials/footer.partial.php" ?>
</body>

</html>