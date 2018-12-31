<?php
@session_start();
include "./funkce/funkce.php";
$db=spojeni();
Menu();
if(isset($_SESSION["login"])){
         header("Location: admin.php");
     }
prihlaseni();
?>
<div class="prihlaseni">
<form action="" method="POST">
    <h1 class="nadpis_vedlejsi_stranka">Přihlášení</h1>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="heslo" placeholder="Heslo" required>
    <input type="submit" name="sended" value="Odeslat" class="odeslat">
</form>

<?php

?>
</div>
<?php
include './body/footer.php';
?>

