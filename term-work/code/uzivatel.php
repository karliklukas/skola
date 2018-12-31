<?php
@session_start();
include "./funkce/funkce.php";
$db=spojeni();
opravneniU();
Menu();


echo "UZIVATEL";

include './body/footer.php';
?>