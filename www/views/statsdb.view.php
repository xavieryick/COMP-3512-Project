<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stats Dashboard</title>
    <link rel="stylesheet" href="/styles/reset.css">
    <link rel="stylesheet" href="/styles/styles.css">
</head>

<body class="container">
    <?php require "partials/header.partial.php" ?>
    <main class="container centered">
        <div>
            <p>Number of photos currently available: <?= $totalPhotos['COUNT(id.ImageID)'] ?></p>
            <p>Most popular city: <?= $popularCity['AsciiName'] ?></p>
            <p>Flagged users (<?= $flaggedUserCount ?>):
            <ul>
                <?php require "partials/flagged.partial.php" ?>
            </ul>
            </p>
        </div>
    </main>
    <?php require "partials/footer.partial.php" ?>
</body>

</html>