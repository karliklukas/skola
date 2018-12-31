<?php
@session_start();
include "./funkce/funkce.php";
$db=spojeni();
Menu();
opravneniA();
?>
<article class="prihlaseni">
<form action="" method="POST">
    <h1 class="nadpis_vedlejsi_stranka">Úprava množství zboží</h1>
    <?php vypisZboziMnozstvi();?>
    <input type="number" name="mnozstvi"  min="0" placeholder="Nové množství" required >
    <input type="submit" name="sended" class="send" value="Odeslat">
</form>
</article>
<?php
mnozstvi();

?>



<?php
include './body/footer.php';
?>
