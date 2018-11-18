<?php
session_start();
include "./funkce.php";
$db=spojeni();
Menu();
?>
<style>

.obalCisel{
    width: 100%;
    margin-top: 3vw;
    text-align: center;
    font-size: 1.5vw;
    color: black;
}
a{
    color: black;
}
.stranky{
    font-weight: normal;
}
.aktualStr{
    color:dodgerblue;
}
    </style>
       

    <a href="zbozi.php?r=abecedne">Abecedne</a><a href="zbozi.php?r=nejlevnejsi">Nejlevnejsi</a><a href="zbozi.php?r=nejdrazsi">Nejdrazsi</a>
            <?php
            
            vypisZbozi();
            ?>

        
        
 
<?php
    include 'footer.php';
?>

