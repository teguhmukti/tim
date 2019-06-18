<?php 
define('host', 'localhost');
define('user', 'root');
define('pass', 'root');
define('db', 'db_perpus');

$conn = mysqli_connect(host, user, pass, db) or die('Unable to Connect');
?>