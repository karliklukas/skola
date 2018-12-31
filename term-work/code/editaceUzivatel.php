<?php
@session_start();
include "./funkce/funkce.php";
$db=spojeni();
Menu();
opravneniU();
if (isset($_GET['id']) && $_SESSION['opravneni']==1) {
    if (!preg_match("/^[0-9]+$/", $_GET['id'])) {
        header("Location:spravaUzivatelu.php");
    }else{
        $sql = "SELECT * FROM `uzivatel` WHERE id={$_GET['id']}";
        editUdaju($_GET['id']);
        editHesla($_GET['id']);
    }

}else{
    $sql = "SELECT * FROM `uzivatel` WHERE id={$_SESSION['id']}";
    editUdaju($_SESSION['id']);
    editHesla($_SESSION['id']);
}
?>
    <div class="prihlaseni">
        <form action="" method="POST">
            <h1 class="nadpis_vedlejsi_stranka">Editace</h1>
            <?php

                if ($data = $db->query($sql)) {
                    if ($data->num_rows > 0) {
                        $row = $data->fetch_assoc();
                        if ($row['opravneni']==1 && $row['id']!=$_SESSION['id']){
                            echo "<h1 class='nadpis_vedlejsi_stranka'>Neplatny uzivatel</h1>";
                        }else{
                            echo " 
                            <input type='text' name='jmeno' placeholder='Jméno' required value='{$row['jmeno']}'>
                            <input type='text' name='prijmeni' placeholder='Příjmení' required value='{$row['prijmeni']}'>
                            <input type='submit' name='sendedD' value='Uložit' class='' class='odeslat'>
                            </form>
                            <h1 class='nadpis_vedlejsi_stranka'>Změna hesla</h1> 
                            <form action='' method='POST'>
                            <input type='password' name='heslo1' placeholder='Heslo' required>
                            <input type='password' name='heslo2' placeholder='Heslo znovu' required>
                            <input type='submit' name='sendedH' value='Změnit heslo' class='odeslat'>
                            </form>
                        ";
                        }

                    }
                }

            ?>
    </div>
<?php
include './body/footer.php';
?>