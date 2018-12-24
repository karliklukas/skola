<?php
@session_start();
include "./funkce.php";
$db=spojeni();
opravneniA();
Menu();
editaceZbozi();
if (isset($_GET['id'])) {
    if (!preg_match("/^[0-9]+$/", $_GET['id'])) {
        return;
    }

    $sql="SELECT `zbozi`.`idzbozi` AS id,`zbozi`.`nazev`, (SELECT cena.cena FROM cena WHERE zbozi_id=`zbozi`.`idzbozi` ORDER BY cena.datum DESC LIMIT 1) AS cena , `zbozi`.`mnozstvi`, 
            `zbozi`.popis, `zbozi`.`kategorie_id`,`zbozi`.`vyrobce_id`
            FROM `zbozi` JOIN `cena` ON `zbozi`.`idzbozi`= `cena`.`zbozi_id` WHERE `zbozi`.`idzbozi`={$_GET['id']} GROUP BY `zbozi`.`nazev`";
    if($data = $db->query($sql)){
        if($data->num_rows > 0){
            $row=$data->fetch_assoc();

            echo "<div class='prihlaseni'><form action='' method='POST' enctype='multipart/form-data'>";
            echo "<h1 class='nadpis_vedlejsi_stranka'>Uprava zboží</h1>";



            echo "<input type='text' name='nazev' placeholder='Název' required value='{$row['nazev']}'>
            <textarea col='40' rows='7' name='popis' placeholder='Stručný popis produktu' class='textarea'>{$row['popis']}</textarea>
            <input type='number' name='mnozstvi'  min='0' placeholder='Množství' required value='{$row['mnozstvi']}'>
            <input type='number' name='cena'  min='0' placeholder='Cena (11.5/20/150.7)' step='0.01'  required value='{$row['cena']}'>";

            $sql2 = "SELECT * FROM `kategorie` ORDER BY `nazev` ASC";
            if($data2 = $db->query($sql2)){
                if($data2->num_rows > 0)
                {
                    echo"<br>";
                    echo "<select name='id_kat' class='select'>";
                    while($row2 = $data2->fetch_assoc())
                    {
                        echo "<option value='$row2[idkategorie]' ".($row["kategorie_id"]==$row2["idkategorie"]?"selected":"").">$row2[nazev]</option>";
                    }
                    echo "</select>";
                } else
                    echo "Nejsou tu zadne kategorie";
            }

            $sql3 = "SELECT * FROM `vyrobce` ORDER BY `nazev` ASC";
            if($data3 = $db->query($sql3)){
                if($data3->num_rows > 0)
                {
                    echo"<br>";
                    echo "<select name='id_vyr' class='select'>";
                    while($row3 = $data3->fetch_assoc())
                    {
                        echo "<option value='$row3[idvyrobce]' ".($row["vyrobce_id"]==$row3["idvyrobce"]?"selected":"").">$row3[nazev]</option>";
                    }
                    echo "</select>";
                } else
                    echo "Nejsou tu zadne kategorie";
            }


            echo "<label class='label'>Vyberte nový obrázek</label>
            <br><div class='fileUpload'>
                <span>Zvolit soubor</span>
                <input type='file' name='files[]' multiple='multiple' class='file22' />
            </div>";

            echo "<input type='submit' name='sended' class='odeslani' value='odeslat'>";
            echo "</form>";
        } else
            echo "Nejsou tu zadna jidla :-(((((";
    }


}


?>

<?php
include './body/footer.php';
?>