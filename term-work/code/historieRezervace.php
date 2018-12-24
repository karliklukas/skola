<?php
@session_start();
include "./funkce.php";
$db=spojeni();
Menu();
opravneniU();
?>


<?php
$dotaz="select * from uzivatel where email='{$_SESSION["login"]}' limit 1";
$data=mysqli_query($db,$dotaz);
$zaznam=mysqli_fetch_array($data);
if($zaznam["opravneni"]!="1"){
    vypisHistorieRezervaci($_SESSION["id"]);
}else {
    vypisHistorieRezervaci(-1);
}




?>



<?php
include './body/footer.php';
?>
