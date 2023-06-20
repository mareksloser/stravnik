<?php
    session_start();
    include_once "inc/db.php";

    global $db;

    // Proměnné
    $perPage = 10; // Počet řádků na stránce
    $pageDefault = 1;
    $average_price_per_meal = 0;

    // Vypočítání průmerné ceny za všechny jídla a zaokrouhlení na padíky
    $average_sql            = "SELECT AVG(price) AS average_price FROM `stravnik`;";
    $stmt                   = $db->query($average_sql);
    $result                 = $stmt->fetch(PDO::FETCH_ASSOC);
    $average_price_per_meal = round($result['average_price'], 2); // round je zaokrouhlení na dvě desetiná místa

    // Stránkování
    if(!isset($_GET['page'])) {
		$page = $pageDefault;
    }

    if(empty($_GET['page'])) {
	    $page = $pageDefault;
    } else {
	    if(!is_numeric($_GET["page"])) {
		    header("location: index.php");
	    }

        $page = $_GET["page"];
    }


    // Vypočítejte počáteční řádek pro aktuální stránku
    $start = ($page - 1) * $perPage;

    // Dotazem získáte celkový počet řádků v tabulce
    $countSql = "SELECT COUNT(*) FROM `stravnik`";
    $countStmt = $db->query($countSql);
    $totalRows = $countStmt->fetchColumn();

    // Vypočítejte celkový počet stránek
    $totalPages = ceil($totalRows / $perPage);

    if($page > $totalPages) {
	    header("location: index.php");
    }

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


        <form class="form" action="create.php" method="POST">
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
                <input class="form-input" type="text" name="food_name" id="food_name">

	            <?php
	            if(isset($_SESSION['errors']['food_name'])) {
		            print '<p class="error-msg">' . $_SESSION['errors']['food_name'] . '</p>';
	            }
	            ?>
            </div>

            <div class="form-control">
                <label class="form-label" for="food_price">Cena jídla:</label>
                <input class="form-input" type="number" name="food_price" id="food_price">

	            <?php
	            if(isset($_SESSION['errors']['food_prize'])) {
		            print '<p class="error-msg">' . $_SESSION['errors']['food_price'] . '</p>';
	            }
	            ?>
            </div>

            <div class="form-control">
                <label class="form-label" for="food_time">Čas jídla:</label>
                <select name="food_time" id="food_time" class="form-select">
                    <option value="rano">rano</option>
                    <option value="poledne">poledne</option>
                    <option value="vecer">vecer</option>
                </select>

	            <?php
	            if(isset($_SESSION['errors']['food_time'])) {
		            print '<p class="error-msg">' . $_SESSION['errors']['food_time'] . '</p>';
	            }
	            ?>
            </div>

            <div class="form-control">
                <input type="submit" name="submit" value="Odeslat">
            </div>
        </form>

        <hr>

        <div class="statistics">
            <h2>Statistika</h2>

            <p>Průměrná cena za jídlo: <?= $average_price_per_meal ?></p>
        </div>

        <hr>

        <div class="food_list">
            <h2>Seznam jídel</h2>

            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Název</th>
                    <th scope="col">Cena</th>
                    <th scope="col">Čas</th>
                    <th scope="col">Akce</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                        $food_list_sql          = "SELECT id, `name`, price, time FROM `stravnik` ORDER BY `id` DESC LIMIT $start, $perPage;";
                        $stmt                   = $db->query($food_list_sql);
                        $foods                  = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($foods as $food)
                        {
                            echo '<tr>';
	                            echo '<th scope="row">' . $food['id'] . '</th>';
	                            echo '<td>' . $food['name'] . '</td>';
	                            echo '<td>' . $food['price'] . ',- Kč</td>';
	                            echo '<td>' . $food['time'] . '</td>';
	                            echo '<td>';
	                                echo '<a href="edit.php?id=' . $food['id'] . '" title="Upravit záznam" class="edit">Upravit</a>';
                                    echo ' ';
	                                echo '<a href="delete.php?id=' . $food['id'] . '" title="Smazat záznam" class="delete">Smazat</a>';
	                            echo '</td>';
	                        echo '</tr>';
                        }


                    ?>
                </tbody>
            </table>

            <div class="pagination">
                <?php
                for ($i = 1; $i <= $totalPages; $i++) {
	                echo '<a href="?page=' . $i . '" title="Stránka ' . $i . '" >' . $i . '</a> ';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>

<?php
    // Po načtení stránky, smazat vše, co je uložené v errors (chyby, zprávy)
    unset($_SESSION["errors"], $_SESSION["info"]);
?>
