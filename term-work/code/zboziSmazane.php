<?php
session_start();
include "./funkce.php";
$db=spojeni();
Menu();
opravneniA();
?>



    <div class="obalTlZbozi">
        <a href="zbozi.php?r=abecedne" class="tlacitko2">Abecedne</a><a href="zbozi.php?r=nejlevnejsi" class="tlacitko2">Nejlevnejsi</a><a href="zbozi.php?r=nejdrazsi" class="tlacitko2">Nejdrazsi</a>
    </div>

<?php

vypisZboziSmazane();
?>




<?php
include './body/footer.php';
?>