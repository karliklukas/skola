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




<?php
if (!preg_match("/^[0-9]+$/", $_GET['id'])) {
    return;
}
$id = $_GET['id'];

$sql = "SELECT `zbozi`.`platnost` FROM zbozi
 WHERE `zbozi`.`idzbozi`={$id}";


if ($data = $db->query($sql)) {
    if ($data->num_rows > 0) {

        $row = $data->fetch_assoc();
        if ($row['platnost'] == 1) {
            echo " <input type='number' name='mnozstvi'  min='0' placeholder='Množství zboží' required >
                    <input type='submit' name='sended' class='send' value='Rezervovat'>";

        }
    }
}

?>
    </form>


<?php
include './body/footer.php';
?>