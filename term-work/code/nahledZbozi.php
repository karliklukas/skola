<?php
@session_start();
include "./funkce.php";
$db=spojeni();
Menu();
rezervace();
?>

    <h1 class="nadpis_vedlejsi_stranka">Náhled zboží</h1>
    <?php nahledZbozi();?>
    <form action="" method="POST">

    <input type="number" name="mnozstvi"  min="0" placeholder="Množství zboží" required >
    <input type="submit" name="sended" class="send" value="Rezervovat">
    </form>

<?php


?>



<?php
include './body/footer.php';
?>