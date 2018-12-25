<?php
@session_start();
include "./funkce.php";
$db=spojeni();
Menu();
opravneniU();
?>


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
