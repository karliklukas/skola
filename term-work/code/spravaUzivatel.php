<?php
@session_start();
include "./funkce/funkce.php";
$db=spojeni();
Menu();
opravneniA();


if (isset($_GET['sm'])) {

       smazaniUzivatele();

}
if (isset($_GET['ob'])) {

    obnoveniUzivatele();

}
?>

    <h1 class="nadpis_vedlejsi_stranka">Výpis uživatelů</h1>
<?php
vypisUzivatelu();


?>



<?php
include './body/footer.php';
?>