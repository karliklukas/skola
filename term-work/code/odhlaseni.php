<?php
     @session_start();

    unset($_SESSION["login"]);
    unset($_SESSION["id"]);
    unset($_SESSION["opravneni"]);
    unset($_SESSION["cart"]);
    echo "<h2>Uzivatel byl odhlasen</h2>";

header("Location: prihlaseni.php");

  ?>
