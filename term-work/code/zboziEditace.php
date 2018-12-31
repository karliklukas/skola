<?php
@session_start();
include "./funkce/funkce.php";
$db=spojeni();
opravneniA();
?>
    <html>
    <head>
        <title>Stavebniny</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css/css.css" type="text/css">
        <link rel="icon" href="./images/logo.png">

        <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="css/froala_editor.css">
        <link rel="stylesheet" href="css/froala_style.css">
        <link rel="stylesheet" href="css/plugins/code_view.css">
        <link rel="stylesheet" href="css/plugins/colors.css">
        <link rel="stylesheet" href="css/plugins/line_breaker.css">
        <link rel="stylesheet" href="css/plugins/char_counter.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.css">
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

        <script type="text/javascript" src="js/froala_editor.min.js" ></script>

        <script type="text/javascript" src="js/plugins/align.min.js"></script>
        <script type="text/javascript" src="js/plugins/code_view.min.js"></script>
        <script type="text/javascript" src="js/plugins/colors.min.js"></script>
        <script type="text/javascript" src="js/plugins/font_size.min.js"></script>
        <script type="text/javascript" src="js/plugins/font_family.min.js"></script>

        <script type="text/javascript" src="js/plugins/line_breaker.min.js"></script>
        <script type="text/javascript" src="js/plugins/link.min.js"></script>
        <script>
            $(function() {
                $('#myEditor').froalaEditor({toolbarInline: false})
                language: 'cs'
            });
        </script>

    </head>
<body>
<header>
    <nav>
        <div id="logo">SYSTÉM PRO STAVEBNINY</div>
        <label for="drop" class="toggle">Menu</label>
        <input type="checkbox" id="drop" />
        <ul class="menu">
            <li><a href="./index.php">Home</a></li>

            <li>
                <label for="drop-1" class="toggle">Zboží +</label>
                <a href="#">Zboží</a>
                <input type="checkbox" id="drop-1"/>
                <ul>
                    <li><a href="./zbozi.php">Nabídka</a></li>
                    <li><a href="./zboziSmazane.php">Smazané zb.</a></li>
                    <li><a href="./upravaZbozi.php">Úprava zboží</a></li>
                    <li><a href="./admin.php">Přidat zboží</a></li>
                </ul>
            </li>
            <li>

                <label for="drop-2" class="toggle">Rezervace +</label>
                <a href="#">Rezervace</a>
                <input type="checkbox" id="drop-2"/>
                <ul>
                    <li><a href="./rezervace.php">Rezervace</a></li>
                    <li><a href="./historieRezervace.php">Historie</a></li>

                </ul>
            </li>
            <li><a href="./pridaniInfo.php">Správa</a></li>
            <li><a href="./spravaUzivatel.php">Uživatelé</a></li>
            <li><a href="kosik.php">Košík</a></li>
            <li class="posledni"><a href="./odhlaseni.php">Odhlásit</a></li>
        </ul>
    </nav>
</header>
    <section id="baner"></section>



<?php
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

            echo "<article class='prihlaseni'><form action='' method='POST' enctype='multipart/form-data'>";
            echo "<h1 class='nadpis_vedlejsi_stranka'>Uprava zboží</h1>";



            echo "<input type='text' name='nazev' placeholder='Název' required value='{$row['nazev']}'>
            <div style=' width: 50%; margin-left: 25%;'>
            <textarea col='40' rows='7' name='popis' placeholder='Stručný popis produktu' id='myEditor' class='textarea'>{$row['popis']}</textarea></div>
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
            echo "</form></article>";
        } else
            echo "Nejsou tu zadna jidla :-(((((";
    }


}


?>

    <footer class="footer">
        <div class="footer1">
            <div class="footer1text1">STAVEBNINY © 2018</div>
        </div>
    </footer>


</body>
    </html>