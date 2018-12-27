<?php
@session_start();
include "./funkce.php";
$db=spojeni();
Menu();
opravneniU();


if ($_SESSION["opravneni"]==1){
    vypisRezervaciPodrobnosti();
}else {
    if (isset($_GET['id'])) {
        if (!preg_match("/^[0-9]+$/", $_GET['id'])) {
            $sqlC = "select * from faktury where uzivatel_id='{$_SESSION["id"]}' AND id={$_GET['id']}";
            if ($dataC = $db->query($sqlC)) {
                if ($dataC->num_rows > 0) {
                    vypisRezervaciPodrobnosti();
                } else {
                    echo "<h1 class='nadpis_vedlejsi_stranka'>Žádné podrobnosti</h1>";
                }
            } else {
                echo "<h1 class='nadpis_vedlejsi_stranka'>Žádné podrobnosti</h1>";
            }
        }
    }else{
        echo "<h1 class='nadpis_vedlejsi_stranka'>Žádné podrobnosti</h1>";
    }
}

?>



<?php
include './body/footer.php';
?>