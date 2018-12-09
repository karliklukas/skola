<?php
@session_start();
include "./funkce.php";
$db=spojeni();
Menu();
?>
<form action="" method="POST">
    <h1 class="nadpis_vedlejsi_stranka">Úprava množství zboží</h1>
    <?php vypisZboziMnozstvi();?>
    <input type="number" name="mnozstvi"  min="0" placeholder="Nové množství" required >
    <input type="submit" name="sended" class="send" value="Odeslat">
</form>

<?php
mnozstvi();

?>



<?php
include './body/footer.php';
?>
