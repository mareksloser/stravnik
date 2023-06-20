<?php
	session_start();
	include_once "inc/db.php";

	global $db;

	// Vytvoření proměnných
	$errors             = []; // vytvoření proměnné, kam budeme ukládat chyby
	$form_food_id       = '';
	$form_food_name     = '';
	$form_food_price    = '';
	$form_food_time     = '';

	// POST
	if(isset($_POST) AND !empty($_POST)) {
		$form_food_id       = $_POST['id'] ?? '';
		$form_food_name     = $_POST['food_name'] ?? '';
		$form_food_price    = $_POST['food_price'] ?? '';
		$form_food_time     = $_POST['food_time'] ?? '';

		// Kontrola jestli bylo vše vloženo
		if(empty($form_food_name)) {
			$errors['food_name'] = 'Vlože prosím název jídla';
		}

		if(empty($form_food_price)) {
			$errors['food_price'] = 'Vlože prosím cenu jídla';
		}

		if(!is_numeric($form_food_price)) {
			$errors['food_price'] .= ', Cena není zapsána jako číslo';
		}

		if(empty($form_food_time)) {
			$errors['food_time'] = 'Vyberte prosím čas jídla';
		}

		if(!is_numeric($form_food_id)) {
			$errors['all'] = 'ID jídla není číslo';
		}

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
			$sql = "UPDATE `stravnik` SET name = :name, price = :price, time = :time WHERE id = :id";
			$dataUpdate = [
				":name" => $form_food_name,
				":price" => $form_food_price,
				":time" => $form_food_time,
				":id" => $form_food_id,
			];

			$sql_prepare = $db->prepare($sql);
			$sql_execute = $sql_prepare->execute($dataUpdate);

			if($sql_execute) {
				$_SESSION['info']['all'] = 'Jídlo bylo upraveno.';
			} else {
				$_SESSION['errors']['all'] = 'Nastala chyba, při upravování jídla v DB.';
			}
		}

	}

	header("location: index.php");