<?php
@session_start();
include "./funkce.php";
$db=spojeni();
opravneniA();
Menu();
editKategorie();
?>
    <div class="prihlaseni">

    <form action="" method="POST" enctype="multipart/form-data">
    <h1 class="nadpis_vedlejsi_stranka">Editace kategorie</h1>
<?php
if (isset($_GET['id'])) {
    if (!preg_match("/^[0-9]+$/", $_GET['id'])) {
        header("Location:pridaniInfo.php");
    }


    $sql = "SELECT * FROM `kategorie` WHERE idkategorie={$_GET['id']}";
    if ($data = $db->query($sql)) {
        if ($data->num_rows > 0) {
            $row = $data->fetch_assoc();
            echo " <input type='text' name='nazev' placeholder='Název kategorie' required value='{$row['nazev']}'>";
        }
    }
}
?>
        <input type="submit" name="sendedK" class="send" value="Odeslat">
        </form>


    </div>


<?php
include './body/footer.php';
?>