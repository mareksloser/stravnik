<?php
	//---heslo SkU2KMUq , https://sql.sps-prosek.cz/phpmyadmin/
	define('DB_NAME', 'hbwhcz_stravnik');
	define('DB_USER', 'hbwhcz_stravnik');
	define('DB_PASSWORD', 'sloser89marek');
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