<?php
session_start();
include "./funkce.php";
$db=spojeni();
Menu();
?>


       
    <div class="obalTlZbozi">
    <a href="zbozi.php?r=abecedne" class="tlacitko2">Abecedně</a><a href="zbozi.php?r=nejlevnejsi" class="tlacitko2">Nejlevnější</a><a href="zbozi.php?r=nejdrazsi" class="tlacitko2">Nejdražší</a>
</div>

            <?php
            
            vypisZbozi();
            ?>

        
        
 
<?php
include './body/footer.php';
?>

