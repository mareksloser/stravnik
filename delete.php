<?php
session_start();
include_once "inc/db.php";

global $db;

// Vytvoření proměnných
$errors             = []; // vytvoření proměnné, kam budeme ukládat chyby
$form_food_id       = '';

// Kontrola, pokud získáme nejaká data z URL adresy, pokud ne, přesměrujeme uživatele na index.php
if(empty($_GET["id"])) {
	header("location: index.php");
}

// Kontrola, jestli bylo získáno číslo v adrese edit.php?id=2, pokud to nebude číslo, přesměrujeme
// uživatele na index.php
// Šlo by to spojit s kontrolou nad, ale pro jednoduchost, máme to takto
if(!is_numeric($_GET["id"])) {
	header("location: index.php");
}

$form_food_id = $_GET["id"];

// Kontrola, jestli ID jídla k úpravě, existuje v DB
$query = "SELECT id FROM `stravnik` WHERE id = :id";
$statement = $db->prepare($query);
$statement->bindParam(':id', $form_food_id);
$statement->execute();

$row = $statement->fetch(PDO::FETCH_ASSOC);

if(!$row) {
	$errors['all'] = 'ID jídla neexistuje';
}

// Pokud se aktivovala nejaká kontrola nahoře, vložíme jí do $_SESSION['errors'], aby se mohla
// zobrazit na index.php
if (count($errors) > 0) {
	$_SESSION['errors'] = $errors;
} else {
	$query = "DELETE FROM `stravnik` WHERE id = :id";
	$stmt = $db->prepare($query);
	$stmt->bindParam(':id', $form_food_id, PDO::PARAM_INT);
	$delete = $stmt->execute();

	if($delete) {
		$_SESSION['info']['all'] = 'Jídlo bylo smazáno.';
	} else {
		$_SESSION['errors']['all'] = 'Nastala chyba, při mazání jídla z DB.';
	}
}

header("location: index.php");