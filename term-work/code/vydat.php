<?php
@session_start();
include "./funkce.php";
$db=spojeni();
Menu();
?>


<?php
vydatZbozi();

?>



<?php
include './body/footer.php';
?>