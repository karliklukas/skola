<?php
@session_start();
include "./funkce.php";
$db=spojeni();
Menu();
opravneniA();


if (isset($_GET['vd'])) {

        vydatZbozil();

}
if (isset($_GET['zr'])) {

    zrusitRezervaciZbozli();

}
?>

    <h1 class="nadpis_vedlejsi_stranka">Výpis uživatelů</h1>
<?php
vypisUzivatelu();


?>



<?php
include './body/footer.php';
?>