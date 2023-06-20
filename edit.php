<?php
session_start();
include_once "inc/db.php";

global $db;

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

// Načteme data z databáze, aby bylo možné, je vložit jako hodnoty do formuláře
$query = "SELECT id, `name`, price, time FROM `stravnik` WHERE id = :id";
$statement = $db->prepare($query);
$statement->bindParam(':id', $_GET["id"]);
$statement->execute();

// Fetch the results
$row = $statement->fetch(PDO::FETCH_ASSOC);

if(!$row) {
	$_SESSION['errors']['all'] = 'Jídlo nebylo nalezeno v DB.';
	header("location: index.php");
}

$food_id            = $row['id'];
$form_food_name     = $row['name'];
$form_food_price    = $row['price'];
$form_food_time     = $row['time'];

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Strávník APP</title>

	<link rel="stylesheet" media="screen" href="css/reset.css" />
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>

<h1 class="main-headline">Strávník - evidence jídel</h1>

<div class="page-content">


	<form class="form" action="update.php" method="POST">
		<h2>Formulář zapsání jídla</h2>

		<?php
		if(isset($_SESSION['errors']['all'])) {
			print '<p class="error-msg">' . $_SESSION['errors']['all'] . '</p>';
		}

		if(isset($_SESSION['info']['all'])) {
			print '<p class="info-msg">' . $_SESSION['info']['all'] . '</p>';
		}
		?>

		<div class="form-control">
			<label class="form-label" for="food_name">Název jídla:</label>
			<input class="form-input" type="text" name="food_name" id="food_name" value="<?= $form_food_name ?>">

			<?php
			if(isset($_SESSION['errors']['food_name'])) {
				print '<p class="error-msg">' . $_SESSION['errors']['food_name'] . '</p>';
			}
			?>
		</div>

		<div class="form-control">
			<label class="form-label" for="food_price">Cena jídla:</label>
			<input class="form-input" type="number" name="food_price" id="food_price" value="<?= $form_food_price ?>">

			<?php
			if(isset($_SESSION['errors']['food_prize'])) {
				print '<p class="error-msg">' . $_SESSION['errors']['food_price'] . '</p>';
			}
			?>
		</div>

		<div class="form-control">
			<label class="form-label" for="food_time">Čas jídla:</label>
			<select name="food_time" id="food_time" class="form-select">
				<option value="rano" <?php if($form_food_time === 'rano') { echo 'selected'; }?>>rano</option>
				<option value="poledne" <?php if($form_food_time === 'poledne') { echo 'selected'; }?>>poledne</option>
				<option value="vecer" <?php if($form_food_time === 'vecer') { echo 'selected'; }?>>vecer</option>
			</select>

			<?php
			if(isset($_SESSION['errors']['food_time'])) {
				print '<p class="error-msg">' . $_SESSION['errors']['food_time'] . '</p>';
			}
			?>
		</div>

		<div class="form-control">
			<input type="hidden" name="id" value="<?= $food_id ?>">
			<input type="submit" name="submit" value="Odeslat">
		</div>
	</form>

</div>
</body>
</html>