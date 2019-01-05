<?php
session_start();
include "./funkce/funkce.php";
$db=spojeni();
Menu();
?>
<script>
    function alertJS() {
        if (confirm("Pro rezervaci se musíte příhlásit!\nPřihlásit se?")) {
            window.location.href = "prihlaseni.php";
        }
    }
    function selectChange() {
        var selector = document.getElementById('kat');
        var value = selector[selector.selectedIndex].value;
        window.location.href = "zbozi.php?k="+String(value);
    }
</script>

       
    <div class="obalTlZbozi">
    <a href="zbozi.php?r=abecedne" class="tlacitko2">Abecedně</a><a href="zbozi.php?r=nejlevnejsi" class="tlacitko2">Nejlevnější</a><a href="zbozi.php?r=nejdrazsi" class="tlacitko2">Nejdražší</a>
        <?php
        $sql = "SELECT * FROM `kategorie` ORDER BY `nazev` ASC";
        if($data = $db->query($sql)){
            if($data->num_rows > 0)
            {
                echo"<br>";
                echo "<select id='kat' name='id_kat' class='select' onchange='selectChange()'>";
                echo "<option value=''>Vyber kategorie</option>";
                while($row = $data->fetch_assoc())
                {
                    if(isset($_GET['k'])){
                        if ($_GET['k']==$row['idkategorie']){
                            echo "<option value='$row[idkategorie]' selected>$row[nazev]</option>";
                        }else {
                            echo "<option value='$row[idkategorie]'>$row[nazev]</option>";
                        }
                    }else{
                        echo "<option value='$row[idkategorie]'>$row[nazev]</option>";
                    }

                }
                echo "</select>";
            } else
                echo "Nejsou tu zadne kategorie";
        }

        ?>
</div>


            <?php
            
            vypisZbozi();
            ?>

        
        
 
<?php
include './body/footer.php';
?>

