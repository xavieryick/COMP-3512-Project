<?php

session_start();
session_regenerate_id(true);
session_destroy();

setcookie("rememberme", "", [
    'domain' => "127.0.0.1",
    'path' => "/",
    'expires' => strtotime('-600 days'),
]);

header("Location: /admin");
die();
