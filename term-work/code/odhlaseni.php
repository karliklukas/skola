<?php
     @session_start();

    unset($_SESSION["login"]);
    echo "<h2>Uzivatel byl odhlasen</h2>";

header("Location: prihlaseni.php");

  ?>
