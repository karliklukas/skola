<?php
@session_start();
include "./funkce.php";
$db=spojeni();
opravneniA();
Menu();
?>
<div class="prihlaseni">

    <form action="" method="POST" enctype="multipart/form-data">
    <h1 class="nadpis_vedlejsi_stranka">Nové zboží</h1>
    <input type="text" name="nazev" placeholder="Název" required>
    <textarea col="40" rows="7" name="popis" placeholder="Stručný popis produktu" class="textarea"></textarea>
    <input type="number" name="mnozstvi"  min="0" placeholder="Množství" required >
    <input type="number" name="cena"  min="0" placeholder="Cena (11.5/20/150.7)" step="0.01"  required >
    <label class="label">Vyberte obrázky k nahrání:</label>
    <br><div class="fileUpload">
    <span>Zvolit soubor</span>
    <input type="file" name="files[]" multiple="multiple" class="file22" />
    </div>
<input type="submit" name="sended" class="send" value="Odeslat">
    </form>

<?php
pridaniZbozi();
?>
</div>

<?php
include './footer.php';
?>