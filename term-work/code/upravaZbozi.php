<?php
session_start();
include "./funkce.php";
$db=spojeni();
Menu();
?>


<?php

vypisZboziUprava();
?>




<?php
include 'footer.php';
?>
