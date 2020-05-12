<?php
ob_start();
session_start();

try {
	// $pdo = new PDO("mysql:dbname=facebook;host=localhost", "root", "hello");
	$pdo = new PDO(
		'mysql:dbname='.DB_NAME.";host=".HOST_NAME,
		DB_USERNAME,
		DB_PASSWORD
	);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch(PDOExeption $e) {
	echo "Connection failed: " . $e->getMessage();
}
?>