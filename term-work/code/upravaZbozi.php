<?php
session_start();
include "./funkce.php";
$db=spojeni();
Menu();
opravneniA();

if (isset($_GET['ob'])) {
    obnovZbozi();
}
if (isset($_GET['sm'])) {
    smazZbozi();
}
?>


<?php

vypisZboziUprava();
?>




<?php
include './body/footer.php';
?>
