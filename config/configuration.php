<?php

$filename = 'sql.sql';
// MySQL host
$mysql_host = 'localhost';
// MySQL username
$mysql_username = 'root';
// MySQL password
$mysql_password = 'p@sswordE123';
// Database name
$mysql_database = 'product_builder';

// Connect to MySQL server
$connection = @new mysqli($mysql_host,$mysql_username,$mysql_password,$mysql_database);