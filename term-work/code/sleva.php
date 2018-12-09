<?php
@session_start();
include "./funkce.php";
$db=spojeni();
Menu();
?>
<form action="" method="POST">
    <h1 class="nadpis_vedlejsi_stranka">Zlevnění zboží</h1>
    <?php vypisZboziSleva();?>
    <input type="number" name="sleva"  min="0" placeholder="Sleva (v %)" required >
<input type="submit" name="sended" class="send" value="Odeslat">
    </form>
            
<?php
sleva();
?>
        
        
 
<?php
    include './body/footer.php';
?>

