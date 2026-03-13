<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="/styles/reset.css">
    <link rel="stylesheet" href="/styles/styles.css">
</head>

<body class="container">
    <?php require "partials/header.partial.php" ?>
    <main class="container centered">
        <form action="/admin/dashboard/stats" method="POST">
            <div id="username-label-input" class="centered">
                <label for="username">Username: </label>
                <input type="text" id="username" name="username" placeholder="Username" value="<?= $username ?>">
            </div>

            <div id="password-label-input" class="centered">
                <label for="password">Password: </label>
                <input type="password" id="password" name="password" placeholder="Password">
            </div>

            <div id="remember-label-input" class="centered">
                <label for="remember-checkbox">Remember me for 30 days: </label>
                <input type="hidden" id="remember-hidden" name="remember" value="n">
                <input type="checkbox" id="remember-checkbox" name="remember" value="y" <?= $checked ?>>
            </div>

            <div id="reset-submit" class="centered">
                <input type="reset" value="Reset" />
                <input type="submit" value="Log In" />
            </div>

            <div id="errors" class='centered'>
                <?php
                if (isset($_SESSION['loginError'])) echo $_SESSION['loginError'];
                unset($_SESSION['loginError']);
                ?>
            </div>
        </form>
    </main>
    <?php require "partials/footer.partial.php" ?>
</body>

</html>