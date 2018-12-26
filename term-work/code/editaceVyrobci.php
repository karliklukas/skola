<?php
@session_start();
include "./funkce.php";
$db=spojeni();
opravneniA();
Menu();
editVyrobce();
?>

    <div class="prihlaseni">

        <form action="" method="POST" enctype="multipart/form-data">
            <h1 class="nadpis_vedlejsi_stranka">Editace výrobce</h1>
            <?php
            if (isset($_GET['id'])) {
                if (!preg_match("/^[0-9]+$/", $_GET['id'])) {
                    header("Location:pridaniInfo.php");
                }

                $sql = "SELECT * FROM `vyrobce` WHERE idvyrobce={$_GET['id']}";
                if ($data = $db->query($sql)) {
                    if ($data->num_rows > 0) {
                        $row = $data->fetch_assoc();
                        echo "<input type='text' name='nazev' placeholder='Název' required value='{$row['nazev']}'>
            <input type='text' name='mesto' placeholder='Město' required value='{$row['mesto']}'>
            <input type='text' name='adresa' placeholder='Ulice č.p.' required value='{$row['adresa']}'>
            <input type='number' name='ico'  min='0' placeholder='ico' required value='{$row['ico']}'>";
                    }
                }
            }
            ?>


            <input type="submit" name="sendedV" class="send" value="Odeslat">
        </form>


    </div>

<?php
include './body/footer.php';
?>