<?php
require_once __DIR__ . '/../database/Connection.php'; //class to manage PDO connection 
$config = require __DIR__ . '/../database/config.php'; //grabs database login details from file
require_once __DIR__ . '/../database/QueryBuilder.php'; //CLASS
require_once __DIR__ . '/../database/SearchOptions.php'; //CLASS
require_once __DIR__ . '/../database/classes/ProjectData.php'; //CLASS
require_once __DIR__ . '/../database/classes/PartData.php'; //CLASS
//require_once 'functions.php';




$dbconn = Connection::make($config['synbioDBconfig']); //connect to database
$searchOptions = new SearchOptions($dbconn);
$queryDB = new QueryBuilder($dbconn); //establish queries to database
