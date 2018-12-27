<?php
@session_start();
include "./funkce.php";
$db=spojeni();
opravneniA();
Menu();
if (isset($_GET['smk'])) {
    smazKategorie();
}
if (isset($_GET['smv'])) {
    smazVyrobce();
}
?>
    <div class="dvaBloky">

        <form action="" method="POST" enctype="multipart/form-data">
            <h1 class="nadpis_vedlejsi_stranka">Nová kategorie</h1>
            <input type="text" name="nazev" placeholder="Název kategorie" required>

            <input type="submit" name="sendedK" class="send" value="Odeslat">
        </form>

        <?php
        pridatKategorie();
        ?>
    </div>

    <div class="dvaBloky">

        <form action="" method="POST" enctype="multipart/form-data">
            <h1 class="nadpis_vedlejsi_stranka">Nový výrobce</h1>
            <input type="text" name="nazev" placeholder="Název" required>
            <input type="text" name="mesto" placeholder="Město" required>
            <input type="text" name="adresa" placeholder="Ulice č.p." required>

            <input type="number" name="ico"  min="0" placeholder="ico" required >

            <input type="submit" name="sendedV" class="send" value="Odeslat">
        </form>

        <form action="" method="POST" enctype="multipart/form-data">
            <label class="label">Vyberte JSON k nahrání:</label>
            <br><div class="fileUpload">
                <span>Zvolit soubor</span>
                <input type="file" name="files" class="file22" />
            </div>

            <input type="submit" name="sendedJ" class="send" value="Odeslat">
        </form>

        <?php
        pridatVyrobce();
        pridatVyrobceJSON();
        ?>
    </div>
<h1 class="nadpis_vedlejsi_stranka">Kategorie</h1>
<?php
vypisKategorieUprava();
?>
<h1 class="nadpis_vedlejsi_stranka">Výrobci</h1>

<?php
vypisVyrobceUprava();
include './body/footer.php';
?>