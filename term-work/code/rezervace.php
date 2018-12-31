<?php
@session_start();
include "./funkce/funkce.php";
$db=spojeni();
Menu();
opravneniU();


  if (isset($_GET['vd'])) {
      if ($_SESSION["opravneni"]==1){
          vydatZbozi();
      }
  }
  if (isset($_GET['zr'])) {
      if ($_SESSION["opravneni"]==1){
          zrusitRezervaciZbozi();
      }else {
          $sqlC = "select * from faktury where uzivatel_id='{$_SESSION["id"]}' AND id={$_GET['id']}";
          if ($dataC = $db->query($sqlC)) {
              if ($dataC->num_rows > 0) {
                  zrusitRezervaciZbozi();
              }
          }
      }

  }
?>

    <h1 class="nadpis_vedlejsi_stranka">Rezervace</h1>
<?php

if($_SESSION["opravneni"]!="1"){
    vypisRezervaci($_SESSION["id"]);
}else {
    vypisRezervaci(-1);
}


?>



<?php
include './body/footer.php';
?>