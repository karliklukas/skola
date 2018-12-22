<?php
@session_start();
include "./funkce.php";
$db=spojeni();
Menu();
?>


<?php
vypisRezervaci();

?>



<?php
include './body/footer.php';
?>