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





<article class="prihlaseni">

    <form action="" method="POST" enctype="multipart/form-data">
    <h1 class="nadpis_vedlejsi_stranka">Nové zboží</h1>
    <input type="text" name="nazev" placeholder="Název" required>
        <div style=" width: 50%; margin-left: 25%;">
        <textarea col="40" rows="7" name="popis" placeholder="Stručný popis produktu" id="myEditor" class="textarea"></textarea></div>
    <input type="number" name="mnozstvi"  min="0" placeholder="Množství" required >
    <input type="number" name="cena"  min="0" placeholder="Cena (11.5/20/150.7)" step="0.01"  required >
        <?php
            vypisKategorie();
            vypisVyrobce();
        ?>
    <label class="label">Vyberte obrázky k nahrání:</label>
    <br><div class="fileUpload">
    <span>Zvolit soubor</span>
    <input type="file" name="files[]" multiple="multiple" accept="image/gif, image/jpeg, image/png" class="file22" />
    </div>
<input type="submit" name="sended" class="send" value="Odeslat">
    </form>

<?php
pridaniZbozi();
?>
</article>

        <footer class="footer">
            <div class="footer1">
                <div class="footer1text1">STAVEBNINY © 2018</div>
            </div>
        </footer>


    </body>
</html>