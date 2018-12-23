
<html>
    <head>
        <title>Stavebniny</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css/css.css" type="text/css">
    </head>
    <body>
        
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
                        <li><a href="./sleva.php">Zlevnit zboží</a></li>
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
                <li><a href="#">Submit</a></li>
                <li><a href="kosik.php">Košík</a></li>
                <li class="posledni"><a href="./odhlaseni.php">Odhlásit</a></li>
            </ul>
        </nav>

        <div id="baner"></div>