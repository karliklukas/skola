<meta charset="UTF-8">
<?php
session_start();
include "funkce.php";


function smazZaka($id){
    $db = spojeni();
    /*$sql = "DELETE FROM znamkovani WHERE zak_id = $id";
    if($db->query($sql) === TRUE){
        echo "Bylo odstraněno";
    } else {
        echo "Není";
    }
    $sql2 = "DELETE FROM archiv WHERE id_zaka = $id";
    if($db->query($sql2) === TRUE){
        echo "Bylo odstraněno";
    } else {
        echo "Není";
    }*/

    $sql1 = "DELETE FROM zbozi WHERE zbozi.id = $id";
    if($db->query($sql1) === TRUE){
        echo "Bylo odstraněno";
    } else {
        echo "Zbozi s id $id u nás nemáme :/";
    }

}


smazZaka($_GET["id"]);
header("Location: upravaZbozi.php");
?>