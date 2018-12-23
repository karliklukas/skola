<?php
session_start();
include "./funkce.php";
error_reporting(E_ERROR | E_PARSE);
$db = spojeni();
Menu();

if ($_GET["action"] == "delete") {
    unset($_SESSION['cart'][$_GET['id']]);
    header("Location:./kosik.php");
}

if (isset($_SESSION['cart']) && sizeof($_SESSION['cart']) > 0) {
    echo "<div class='obalTable'><table class='table1'>
    <thead>
    <tr>
        <th>Název</th>
        <th>Množství</th>
        <th>Cena</th>
        <th>Smazat</th>
    </tr>
    </thead>";

    foreach ($_SESSION['cart'] as $cartItemId => $value) {
        echo "<tr>";

        $sql = "SELECT `zbozi`.`nazev` FROM `zbozi` WHERE `zbozi`.`idzbozi`={$cartItemId}";
        if ($data = $db->query($sql)) {
            if ($data->num_rows > 0) {
                $row = $data->fetch_assoc();
                echo "<td>" . $row['nazev'] . "</td>";
            }
        }
        echo "<td>" . $value['quantity'] . "</td>";

        $sql = "SELECT `zbozi`.`idzbozi` AS id,`zbozi`.`nazev`, (SELECT cena.cena FROM cena WHERE zbozi_id=`zbozi`.`idzbozi` ORDER BY cena.datum DESC LIMIT 1) AS cena , `zbozi`.`mnozstvi`, `zbozi`.popis
            FROM `zbozi` JOIN `cena` ON `zbozi`.`idzbozi`= `cena`.`zbozi_id` WHERE `zbozi`.`idzbozi`={$cartItemId} GROUP BY `zbozi`.`nazev`";
        if ($data = $db->query($sql)) {
            if ($data->num_rows > 0) {
                $row = $data->fetch_assoc();
                $cena = $row['cena'] * $value['quantity'];
                echo "<td>" . $cena . " Kc</td>";
            }
        }

        echo "<td>
            
            <a href='./kosik.php?action=delete&id=" . $cartItemId . "' class=\"btn btn-primary\">X</a>
            </td>";
        echo "</tr>";
    }

    echo "</table></div>";
} else {
    echo "<h1 class='nadpis_vedlejsi_stranka'>Košík je prázdný</h1>";
}

/////////////////////////////ulozeni

if (isset($_POST["sended"])) {
    if (isset($_SESSION['cart']) && sizeof($_SESSION['cart']) > 0) {
        $sql5 = "INSERT INTO `faktury` (`uzivatel_id`) VALUES (?);";
        if ($stmt = $db->prepare($sql5)) {
            $stmt->bind_param("i", $_SESSION["id"]);
            $stmt->execute();
            $id = $stmt->insert_id;
            foreach ($_SESSION['cart'] as $cartItemId => $value) {
                $sqlCena = "SELECT * FROM cena WHERE zbozi_id={$cartItemId} ORDER BY cena.datum DESC LIMIT 1";
                if ($data = $db->query($sqlCena)) {
                    if ($data->num_rows > 0) {
                        $row = $data->fetch_assoc();
                        $idCena = $row['id'];

                    }
                }

                $sql = "INSERT INTO `objednavky` (`mnozstvi`, `faktury_id`, `cena_id`) VALUES (?,?,?);";
                if ($stmt = $db->prepare($sql)) {
                    $stmt->bind_param("iii", $value['quantity'], $id, $idCena);
                    $stmt->execute();


                }

            }
            unset($_SESSION['cart']);
            header("Location:./kosik.php");
        } else {
            echo "<p class='hlaska'>Nastava chyba</p>";
        }
    }
}


?>

    <form action="" method="POST">
        <input type="submit" name="sended" class="send" value="Zarezervovat">
    </form>

<?php
include './body/footer.php';
?>