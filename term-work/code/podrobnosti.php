<?php
@session_start();
include "./funkce.php";
$db=spojeni();
Menu();
?>


<?php
vypisRezervaciPodrobnosti();

?>



<?php
include './body/footer.php';
?>