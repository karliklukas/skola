<?php
@session_start();
include "./funkce.php";
$db=spojeni();
Menu();
opravneniU();
?>

<h1 class="nadpis_vedlejsi_stranka">Histore rezervace</h1>
<?php
if($_SESSION["opravneni"]!="1"){
    vypisHistorieRezervaci($_SESSION["id"]);
}else {
    vypisHistorieRezervaci(-1);
}




?>



<?php
include './body/footer.php';
?>
