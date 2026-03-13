<?php

// admin functions
$router->add("GET", "/admin/dashboard/photos", "admin/photosdb.php")->restricted();
$router->add("GET", "/admin/dashboard/stats", "admin/statsdb.php")->restricted();
$router->add("GET", "/admin/logout", "admin/logout.php");
$router->add("GET", "/admin", "form/create.php");
$router->add("POST", "/admin/dashboard/stats", "form/store.php");

// photo functions
$router->add("POST", '/admin/dashboard/photos/flag', "photo/flagPhoto.php");
$router->add("POST", '/admin/dashboard/photos/unflag', "photo/unflagPhoto.php");
$router->add("POST", '/admin/dashboard/photos/delete', "photo/deletePhoto.php");
$router->add("POST", '/admin/dashboard/photos/previous', 'photo/previous.php');
$router->add("POST", '/admin/dashboard/photos/next', 'photo/next.php');
$router->add("POST", '/admin/dashboard/photos/previous10', 'photo/previous10.php');
$router->add("POST", '/admin/dashboard/photos/next10', 'photo/next10.php');

// sorting functions
$router->add("GET", '/admin/dashboard/photos/idasc', 'sort/idasc.php');
$router->add("GET", '/admin/dashboard/photos/iddesc', 'sort/iddesc.php');
$router->add("GET", '/admin/dashboard/photos/titleasc', 'sort/titleasc.php');
$router->add("GET", '/admin/dashboard/photos/titledesc', 'sort/titledesc.php');
$router->add("GET", '/admin/dashboard/photos/nameasc', 'sort/nameasc.php');
$router->add("GET", '/admin/dashboard/photos/namedesc', 'sort/namedesc.php');

// search page stuff
$router->add("GET", '/', '../views/search.view.php');
$router->add("GET", '/matches', '../views/search.view.php');
$router->add("GET", '/details', '../views/search.view.php');
$router->add("GET", '/photo', '../views/search.view.php');
// $router->add("GET", '/test', 'test.php');
