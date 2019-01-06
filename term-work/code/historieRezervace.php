<?php
@session_start();
include "./funkce/funkce.php";
$db=spojeni();

Menu();
opravneniU();
if (isset($_GET['vd'])) {
    if ($_SESSION["opravneni"]==1){
        toJSON(-1);
    }else{
        toJSON($_SESSION['id']);
    }
}
if (isset($_GET['fk'])) {
    if ($_SESSION["opravneni"]==1){
        vyfakturovat();
    }
}


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
