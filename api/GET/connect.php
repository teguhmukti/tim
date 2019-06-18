<?php 

define('host','localhost');
define('name', 'root');
define('pass', 'root');
define('dbase', 'db_perpus');

$conn = mysqli_connect(host, name, pass, dbase) or die('Unable to connect');

?>