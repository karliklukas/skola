<?php
@session_start();
include "./funkce.php";
$db=spojeni();
Menu();
?>


<?php
vypisHistorieRezervaci();

?>



<?php
include './body/footer.php';
?>
