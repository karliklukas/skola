<?php
@session_start();
include "./funkce/funkce.php";
$db=spojeni();
Menu();
?>
<div class="prihlaseni">
<form action="" method="POST">
    <h1 class="nadpis_vedlejsi_stranka">Registrace</h1>
    <input type="text" name="jmeno" placeholder="Jméno" required>
    <input type="text" name="prijmeni" placeholder="Příjmení" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="heslo1" placeholder="Heslo" required>
    <input type="password" name="heslo2" placeholder="Heslo znovu" required>
    <input type="submit" name="sended" value="Odeslat" class="odeslat">
</form>

<?php
registrace();
?>
</div>
<?php
include './body/footer.php';
?>

