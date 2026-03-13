<?php

require "../database/DatabaseQueries.php";
$queries = new DatabaseQueries();
$imageid = (int) $_POST['imageid'];
$queries->flag($imageid);

header("Location: /admin/dashboard/photos");
die();
