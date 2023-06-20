<?php
	//---heslo SkU2KMUq , https://sql.sps-prosek.cz/phpmyadmin/
	define('DB_NAME', '');
	define('DB_USER', '');
	define('DB_PASSWORD', '');
	define('DB_HOST', '127.0.0.1');

	global $db;

    $db = new PDO(
            "mysql:host=" .DB_HOST. ";dbname=" .DB_NAME,
            DB_USER,
            DB_PASSWORD,
            [
	            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            ]
    );
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>