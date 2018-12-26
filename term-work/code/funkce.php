<?php

function spojeni() {
    $db = new mysqli("localhost", "root", "", "staveb");
    if ($db->errno > 0)
        die("Je to rozbité :(");
    $db->set_charset("utf8");
    return $db;
}

function registrace() {
    $db = spojeni();
    if (isset($_POST["sended"])) {
        if (empty($_POST["jmeno"]) || empty($_POST["heslo1"]) || empty($_POST["prijmeni"]) || empty($_POST["heslo2"]) || empty($_POST["email"])) {
            echo "<p class='hlaska'>Vyplň formulář</p>";
        } else if ($_POST["heslo1"] != $_POST["heslo2"]) {
            echo "<p class='hlaska'>Hesla nejsou stejná.</p>";
        } else {
            $jmeno = $_POST["jmeno"];
            $prijmeni = $_POST["prijmeni"];
            $email = $_POST["email"];
            $heslo = password_hash($_POST["heslo1"], PASSWORD_BCRYPT);
            $chyba = 0;


            $sql = "SELECT * FROM uzivatel WHERE email='" . $email . "'";
            if ($data = $db->query($sql)) {
                if ($data->num_rows > 0) {
                    $chyba = 1;
                }
            }

            if ($chyba == 1) {
                echo "<p class='hlaska'>Tento email již je v naší databázi.</p>";
            } else {

                $sql5 = "INSERT INTO `uzivatel` (`jmeno`, `prijmeni`, `email`, `heslo`) VALUES (?,?,?,?);";
                if ($stmt = $db->prepare($sql5)) {
                    $stmt->bind_param("ssss", $jmeno, $prijmeni, $email, $heslo);
                    $stmt->execute();
                    $id = $stmt->insert_id;

                    echo "<p class='hlaska'>Jste zaregistrováni, můžete se přihlásit</p>";
                }
            }
        }
    }
}

function prihlaseni() {
    $db = spojeni();
    if (!empty($_POST["email"]) && !empty($_POST["heslo"])) {
        $login = $_POST["email"];
        $heslo = $_POST["heslo"];

        $stmt = $db->prepare("select * from uzivatel where email=? AND platnost=1 limit 1");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();
        $zaznam = $result->fetch_assoc();


        if (password_verify($_POST["heslo"], $zaznam["heslo"])) {
            $_SESSION["login"] = $zaznam["email"];
            $_SESSION["id"] = $zaznam["id"];
            $_SESSION["opravneni"] = $zaznam["opravneni"];
            rozrazeni();
        } else {
            echo "<h1 class='nadpis_vedlejsi_stranka'>Zadali jste špatné údaje, zkuste to prosím znovu.</h1>";
        }
    }
}

function rozrazeni() {
    $db = spojeni();

    if (!isset($_SESSION["login"])) {
        header("Location: index.php");
    } else {
        $dotaz = "select * from uzivatel where email='{$_SESSION["login"]}' limit 1";
        $data = mysqli_query($db, $dotaz);
        $zaznam = mysqli_fetch_array($data);
        if ($zaznam["opravneni"] == "0") {
            header("Location: rezervace.php");
        } else if ($zaznam["opravneni"] == "1") {
            header("Location: admin.php");
        }
    }
}

function opravneniA(){
    $db= spojeni();
    
    if(!isset($_SESSION["login"])) {
        header("Location: prihlaseni.php");    
} else {    
    $dotaz="select * from uzivatel where email='{$_SESSION["login"]}' limit 1";
     $data=mysqli_query($db,$dotaz);
     $zaznam=mysqli_fetch_array($data);
     if($zaznam["opravneni"]!="1"){header("Location: uzivatel.php");}
}
}

function opravneniU(){
    $db= spojeni();
    
    if(!isset($_SESSION["login"])) {
        header("Location: prihlaseni.php");    
} else {    
    $dotaz="select * from uzivatel where email='{$_SESSION["login"]}' limit 1";
     $data=mysqli_query($db,$dotaz);
     if(!$zaznam=mysqli_fetch_array($data)){header("Location: prihlaseni.php");}
}
}

function Menu(){
    $db= spojeni();
    
    if(isset($_SESSION["login"])) { 
     $dotaz="select * from uzivatel where email='{$_SESSION["login"]}' limit 1";
     $data=mysqli_query($db,$dotaz);
     $zaznam=mysqli_fetch_array($data);
     if($zaznam["opravneni"]=="0"){
         include './body/header_user.php';}
     else if($zaznam["opravneni"]=="1"){
         include './body/header_admin.php';}
} else {    
     include './body/header.php';
}
}

function pridaniZbozi() {
    $db = spojeni();
    if (isset($_POST["sended"])) {
        if (empty($_POST["nazev"]) || empty($_POST["popis"]) || empty($_POST["id_vyr"])|| empty($_POST["id_kat"])|| empty($_POST["cena"])|| empty($_POST["mnozstvi"])) {
            echo "Vyplň formulář ";
            echo $_POST["id_vyr"];
            echo $_POST["id_kat"];
        } else {
            $nazev = $_POST["nazev"];
            $popis = $_POST["popis"];
            $cena = $_POST["cena"];
            $mnozstvi = $_POST["mnozstvi"];
            $vyrobce = $_POST["id_vyr"];
            $kategorie = $_POST["id_kat"];
            
            $cena=  floatval(str_replace(',', '.', $cena));

            

            $sql = "INSERT INTO `zbozi` (`nazev`, `popis`, `mnozstvi`, `vyrobce_id`, `kategorie_id`) VALUES (?,?,?,?,?);";
            if ($stmt = $db->prepare($sql)) {
                $stmt->bind_param("ssiii", $nazev, $popis,$mnozstvi,$vyrobce,$kategorie);
                $stmt->execute();
                $id = $stmt->insert_id;
            }

            $sql1 = "INSERT INTO `cena` (`cena`, `zbozi_id`) VALUES (?,?)";
            if ($stmt = $db->prepare($sql1)) {
                $stmt->bind_param("di", $cena,$id);
                $stmt->execute();
                //$id = $stmt->insert_id;
            }

            $errors = array();
            $uploadedFiles = array();
            $extension = array("jpeg", "jpg", "png", "gif", "PNG", "JPG", "GIF", "JPEG");
            $bytes = 1024;
            $KB = 4096;
            $totalBytes = $bytes * $KB;
            $UploadFolder = "images/obrazkyZbozi/" . $id;

            $counter = 0;

            foreach ($_FILES["files"]["tmp_name"] as $key => $tmp_name) {
                $temp = $_FILES["files"]["tmp_name"][$key];
                $name = $_FILES["files"]["name"][$key];

                $pripona = substr($name, strpos($name,"."));

                if (!is_dir($UploadFolder)) {
                    mkdir($UploadFolder);
                }

                if (empty($temp)) {
                    break;
                }

                $counter++;
                $UploadOk = true;

                if ($_FILES["files"]["size"][$key] > $totalBytes) {
                    $UploadOk = false;
                    array_push($errors, $name . " obrázek je větší než 4 MB.");
                }

                $ext = pathinfo($name, PATHINFO_EXTENSION);
                if (in_array($ext, $extension) == false) {
                    $UploadOk = false;
                    array_push($errors, $name . " špatný typ obrázku.");
                }

                if (file_exists($UploadFolder . "/" . $name) == true) {
                    $UploadOk = false;
                    array_push($errors, $name . " obrázek již existuje.");
                }

                if ($UploadOk == true) {
                    move_uploaded_file($temp, $UploadFolder . "/" . $id ."".$pripona);
                    array_push($uploadedFiles, $name);
                }
            }

            if ($counter > 0) {
                if (count($errors) > 0) {
                    echo "<b>Chyby:</b>";
                    echo "<br/><ul>";
                    foreach ($errors as $error) {
                        echo "<li>" . $error . "</li>";
                    }
                    echo "</ul><br/>";
                }
                
                
                if (count($uploadedFiles) > 0) {                //tenhle if je asi k ničemu, dřív vypisoval uložené obrázky, můžeš ho zkusit smazat
                    foreach ($uploadedFiles as $fileName) {
                    }
                }
                
            } else {
                echo "Prosím vyberte obrázky k nahrání";
            }
        }
    }
}

function editaceZbozi() {
    $db = spojeni();
    if (isset($_POST["sended"])) {
        if (empty($_POST["nazev"]) || empty($_POST["popis"]) || empty($_POST["id_vyr"])|| empty($_POST["id_kat"])|| empty($_POST["cena"])|| empty($_POST["mnozstvi"])) {
            echo "Vyplň formulář ";
            echo $_POST["id_vyr"];
            echo $_POST["id_kat"];
        } else {
            if (!preg_match("/^[0-9]+$/", $_GET['id'])) {
                return;
            }
            $nazev = $_POST["nazev"];
            $popis = $_POST["popis"];
            $cena = $_POST["cena"];
            $mnozstvi = $_POST["mnozstvi"];
            $vyrobce = $_POST["id_vyr"];
            $kategorie = $_POST["id_kat"];
            $idZ = $_GET['id'];
            $cena=  floatval(str_replace(',', '.', $cena));



            $sql = "UPDATE `zbozi` SET `nazev` = ?, `popis` = ?, `mnozstvi` = ?, `vyrobce_id` = ?, `kategorie_id` = ? WHERE `zbozi`.`idzbozi` = {$idZ}";
            if ($stmt = $db->prepare($sql)) {
                $stmt->bind_param("ssiii", $nazev, $popis,$mnozstvi,$vyrobce,$kategorie);
                $stmt->execute();

            }

            $sql1 = "INSERT INTO `cena` (`cena`, `zbozi_id`) VALUES (?,?)";
            if ($stmt = $db->prepare($sql1)) {
                $stmt->bind_param("di", $cena,$idZ);
                $stmt->execute();
            }

            $errors = array();
            $uploadedFiles = array();
            $extension = array("jpeg", "jpg", "png", "gif", "PNG", "JPG", "GIF", "JPEG");
            $bytes = 1024;
            $KB = 4096;
            $totalBytes = $bytes * $KB;
            $UploadFolder = "images/obrazkyZbozi/" . $idZ;

            $counter = 0;
            //mazani starenho obr
            if (is_dir($UploadFolder))
                $dir_handle = opendir($UploadFolder);
            while($file = readdir($dir_handle)) {
                if ($file != "." && $file != "..") {
                    unlink($UploadFolder . "/" . $file);
                }
            }
            closedir($dir_handle);
            /////

            foreach ($_FILES["files"]["tmp_name"] as $key => $tmp_name) {
                $temp = $_FILES["files"]["tmp_name"][$key];
                $name = $_FILES["files"]["name"][$key];

                $pripona = substr($name, strpos($name,"."));

                if (!is_dir($UploadFolder)) {
                    mkdir($UploadFolder);
                }

                if (empty($temp)) {
                    break;
                }

                $counter++;
                $UploadOk = true;

                if ($_FILES["files"]["size"][$key] > $totalBytes) {
                    $UploadOk = false;
                    array_push($errors, $name . " obrázek je větší než 4 MB.");
                }

                $ext = pathinfo($name, PATHINFO_EXTENSION);
                if (in_array($ext, $extension) == false) {
                    $UploadOk = false;
                    array_push($errors, $name . " špatný typ obrázku.");
                }

                if (file_exists($UploadFolder . "/" . $name) == true) {
                    $UploadOk = false;
                    array_push($errors, $name . " obrázek již existuje.");
                }

                if ($UploadOk == true) {
                    move_uploaded_file($temp, $UploadFolder . "/" . $idZ ."".$pripona);
                    array_push($uploadedFiles, $name);
                }
            }

            if ($counter > 0) {
                if (count($errors) > 0) {
                    echo "<b>Chyby:</b>";
                    echo "<br/><ul>";
                    foreach ($errors as $error) {
                        echo "<li>" . $error . "</li>";
                    }
                    echo "</ul><br/>";
                }


                if (count($uploadedFiles) > 0) {                //tenhle if je asi k ničemu, dřív vypisoval uložené obrázky, můžeš ho zkusit smazat
                    foreach ($uploadedFiles as $fileName) {
                    }
                }

            }else{
                echo "podas";
            }
            //header("Location: upravaZbozi.php");
        }
    }
}

function vypisZbozi(){
    $db = spojeni();

    if (isset($_GET['st'])) {
        if (!preg_match("/^[0-9]+$/", $_GET['st'])) {
            $strana = 1;
        } else {
            $strana = $_GET['st'];
        }
    } else {
        $strana = 1;
    }
    $naStranu = 6;
    $pocetStranData = "SELECT COUNT(*) FROM `zbozi` WHERE `zbozi`.`platnost`=1";
    $dataS = mysqli_query($db, $pocetStranData);
    $stran = mysqli_fetch_array($dataS);
    $pocet = ($strana - 1) * $naStranu;
    $pocetStran = ceil($stran[0] / $naStranu);
    if (isset($_GET['st'])) {
        if ($_GET['st'] > $pocetStran) {
            $strana = 1;
            $pocet = 1;
            $naStranu = 6;
        }
    }


    if (isset($_GET['r'])) {
        if (!preg_match("/^[a-z]+$/", $_GET['r'])) {
            $razeni = "id";
            $zpusob="DESC";
        } else {
            $radit = $_GET['r'];
            if ($radit == "abecedne") {
                $razeni = "nazev";
                $zpusob="ASC";
            }elseif ($radit == "nejlevnejsi") {
                $razeni = "cena";
                $zpusob="ASC";
            }elseif($radit == "nejdrazsi"){
                $razeni = "cena";
                $zpusob="DESC";
            }else{
                $razeni = "id";
                $zpusob="DESC";
            }
        }
    } else {
        $razeni = "id";
        $zpusob="DESC";
    }



    $sql = "SELECT `zbozi`.`idzbozi` AS id,`zbozi`.`nazev`, (SELECT cena.cena FROM cena WHERE zbozi_id=`zbozi`.`idzbozi` ORDER BY cena.datum DESC LIMIT 1) AS cena , `zbozi`.`mnozstvi`, `zbozi`.popis
            FROM `zbozi` JOIN `cena` ON `zbozi`.`idzbozi`= `cena`.`zbozi_id` WHERE `zbozi`.`platnost`=1 GROUP BY `zbozi`.`nazev` ORDER BY $razeni $zpusob LIMIT $pocet, $naStranu";

    echo "<div class='cl'>";
    if ($data = $db->query($sql)) {
        if ($data->num_rows > 0) {

            while ($row = $data->fetch_assoc()) {
                echo "<div class='article'>";
                echo "<div class='foto1'>";

                $nazevSlozky = "images/obrazkyZbozi/" . $row['id'];
                if (is_dir($nazevSlozky)) {
                    $slozka = opendir($nazevSlozky);
                    for ($i = 0; $i < 1; $i++) {
                        $files = scandir($nazevSlozky);
                        $pocetSoub = count($files);
                        while ($nazevSouboru = readdir($slozka)) {
                            if ($nazevSouboru != "." && $nazevSouboru != ".." && $nazevSouboru != "nahledy" && $nazevSouboru != "thumbs.db" && $nazevSouboru != "Thumbs.db") {

                                echo "<img src='$nazevSlozky/$nazevSouboru' alt='' class='zkft'>";
                                break;
                            } else if ($pocetSoub <= 2) {

                                $odkaz = "images/1.jpg";
                                echo "<img src='$odkaz' alt='' class='zkft'>";
                                break;
                            }
                        }
                    }
                }

                echo "</div>";

                /*if ($row['sleva'] != 0) {
                    $text = "– zlevněno " . $row['sleva'] . "%";
                    $cenaPred = ", (cena před " . $row['cena'] . " Kč)";
                    $cena = $row['cena'] - $row['cena'] / $row['sleva'];
                } else {
                    $text = "";
                    $cena = $row['cena'];
                    $cenaPred = "";
                }*/

                $sqlCena = "SELECT SUM(`objednavky`.`mnozstvi`) AS rez
                            FROM `objednavky` JOIN `faktury` ON `objednavky`.`faktury_id`=faktury.id JOIN `cena` ON `cena`.`id`=`objednavky`.cena_id
                            WHERE `faktury`.`datum_vydani` IS NULL AND `cena`.`zbozi_id`={$row['id']}";
                if ($dataR = $db->query($sqlCena)) {
                    if ($dataR->num_rows > 0) {
                        $rowR = $dataR->fetch_assoc();
                        $mnozRez = $row['mnozstvi']-$rowR['rez'];

                    }
                }

                echo" <div class='cln1'>
                                <h1 class='name'>{$row['nazev']}</h1>
                                <h1 class='name1'>Cena: {$row['cena']} Kč</h1>
                                <h1 class='name1'>Množství: {$mnozRez} kusů</h1>";

                //Oříznutí popisu

                                $znak = strpos($row['popis'],'</p>');
                                if ($znak<60) {
                                echo substr($row['popis'], 0, $znak);
                                //echo "...";
                                }else if(strlen($row['popis'])>=60){
                                echo substr($row['popis'], 0, 60);
                                echo "...";
                                }else{
                 echo"<p class='popisZbozi'>";
                echo $row['popis'];
                echo"</p>";
                                }

                echo"</div>";
                if(!isset($_SESSION["login"])){
                    echo "<br><a href='#' onclick='alertJS();return false;' class='a'><button class='tlacitko1'>REZERVOVAT</button></a>"; //dát správný odkaz
                }else{
                    echo "<br><a href='nahledZbozi.php?id=$row[id]' class='a'><button class='tlacitko1'>REZERVOVAT</button></a>"; //dát správný odkaz
                }


                echo"</div>";
            }echo"</div>";

            echo"<div class='obalCisel'>";

            if (!isset($_GET["st"]))
                $i = 1;
            else {
                $i = $_GET['st'];
                if (!preg_match("/^[0-9]+$/", $_GET['st'])) {
                    $i = 1;
                }
            }
            if (isset($_GET['st'])) {
                if ($_GET['st'] > $pocetStran) {
                    $i = 1;
                }
            }

            if ($i > 1) {
                if(isset($_GET['r'])){
                    echo "<a href='zbozi.php?st=1&r=" . ($_GET['r']) . "'>&lt;&lt; </a>";
                }else {
                    echo "<a href='zbozi.php?st=1'>&lt;&lt; </a>";
                }                
            }

// PŘEDCHOZÍ
            if ($i > 1) {
                if(isset($_GET['r'])){
                echo "<a class='stranky'  href='zbozi.php?st=" . ($i - 1) . "&r=" . ($_GET['r']) . "'> &lt; </a>";
                }else {
                    echo "<a class='stranky'  href='zbozi.php?st=" . ($i - 1) . "'> &lt; </a>";
                    }
// PŘEDCHOZÍ - CYKLUS
                for ($j = 4; $j > 0; $j--) {
                    if (($i - $j) >= 1) {
                        if(isset($_GET['r'])){
                            echo "<a class='stranky' href='zbozi.php?st=" . ($i - $j) . "&r=" . ($_GET['r']) . "'>" . ($i - $j) . "</a>";
                        }else{
                            echo "<a class='stranky' href='zbozi.php?st=" . ($i - $j) . "'>" . ($i - $j) . "</a>";
                        }                        
                    }
                }
            }

            echo "<b class='aktualStr'>" . $i . "</b>";

// DALŠÍ
            if ($i < ($pocetStran)) {
                // DALŠÍ - CYKLUS
                for ($m = 1; $m < 4; $m++) {
                    if (($i + $m) <= ceil($pocetStran)) {
                        if(isset($_GET['r'])){
                            echo "<a class='stranky' href='zbozi.php?st=" . ($i + $m) . "&r=" . ($_GET['r']) . "'>" . ($i + $m) . "</a>";
                        }else {
                             echo "<a class='stranky' href='zbozi.php?st=" . ($i + $m) . "'>" . ($i + $m) . "</a>";
                        }                       
                    }
                }

// DALŠÍ        
                if(isset($_GET['r'])){
                    echo "<a class='stranky' href='zbozi.php?st=" . ($i + 1) . "&r=" . ($_GET['r']) . "'> &gt; </a>";
                }else {
                    echo "<a class='stranky' href='zbozi.php?st=" . ($i + 1) . "'> &gt; </a>";
                }
                
            }


// KONEC
            if ($i < ceil($pocetStran)) {
                if(isset($_GET['r'])){
                    echo "<a class='stranky' href='zbozi.php?st=" . ceil($pocetStran) . "&r=" . ($_GET['r']) . "'> &gt;&gt;</a>";
                }else{
                    echo "<a class='stranky' href='zbozi.php?st=" . ceil($pocetStran) . "'> &gt;&gt;</a>";
                }
                
            }

            echo "</div>";
        } else
            echo "<p class='hlaska'>Omlouváme se, ale chybí nám tu zbozi.</p>";
    }
}

function vypisZboziSleva(){ 
		$db= spojeni();
                $sql="SELECT * FROM zbozi ORDER BY `nazev` ASC";
		if($data = $db->query($sql)){
			if($data->num_rows > 0)
			{
                                echo"<br>";
                                echo "<select name='id_zbozi' class='select'>";
				while($row = $data->fetch_assoc())
				{					
					echo "<option value='$row[id]'>$row[nazev]</option>";					
				}
				echo "</select>";
			} else 
				echo "<p class='hlaska'>Není tu zboží.</p>";
                }else{
                    echo "<p class='hlaska'>Není tu zboží.</p>";
                }	
	}

function vypisZboziMnozstvi(){
    $db= spojeni();

    if (isset($_GET['id'])) {
        if (!preg_match("/^[0-9]+$/", $_GET['id'])) {
            return;
        }

    $sql="SELECT * FROM zbozi WHERE idzbozi = {$_GET['id']} ORDER BY `nazev` ASC";
    if($data = $db->query($sql)){
        if($data->num_rows > 0)
        {
            while($row = $data->fetch_assoc())
            {
                echo"<br>";
                echo "<p class='hlaska'><b>Zboží:</b> {$row['nazev']} <b>Staré množství:</b> $row[mnozstvi] </p>";

            }



        } else
            echo "<p class='hlaska'>Není tu zboží.</p>";
    }else{
        echo "<p class='hlaska'>Není tu zboží.</p>";
    }
}
}
        
function sleva() {
    $db = spojeni();
    $nula=0;
    if (isset($_POST["sended"])) {
        if (empty($_POST["sleva"])&& $_POST["sleva"] != $nula) {
            echo "<p class='hlaska'>Vyplň formulář</p>";
        } else if($_POST["sleva"]==$nula) {
            $sleva = 0;
            $zbozi = $_POST["id_zbozi"];
            $sql = "UPDATE zbozi SET sleva = ? WHERE id = ".$zbozi.";";
            if ($stmt = $db->prepare($sql)) {
                $stmt->bind_param("i", $sleva);
                $stmt->execute();
                $id = $stmt->insert_id;
            }else{
                echo "CHYBA";
            }
        }else{
            $sleva = $_POST["sleva"];
            $zbozi = $_POST["id_zbozi"];

            $sql = "UPDATE zbozi SET sleva = ? WHERE id = ".$zbozi.";";
            if ($stmt = $db->prepare($sql)) {
                $stmt->bind_param("i", $sleva);
                $stmt->execute();
                $id = $stmt->insert_id;
            }else{
                echo "CHYBA";
            }
        }
    }
}

function mnozstvi() {
    $db = spojeni();

    if (isset($_POST["sended"])) {
        if (empty($_POST["mnozstvi"])) {
            echo "<p class='hlaska'>Vyplň formulář</p>";
        } else {
            if (!preg_match("/^[0-9]+$/", $_GET['id'])) {
                return;
            }
            $id = $_GET['id'];
            $mn = $_POST["mnozstvi"];
            $sql = "UPDATE zbozi SET mnozstvi = ? WHERE idzbozi = ".$id.";";
            if ($stmt = $db->prepare($sql)) {
                $stmt->bind_param("i", $mn);
                $stmt->execute();
                $id = $stmt->insert_id;
                header("Location: upravaZbozi.php");
            }else{
                echo "CHYBA";
            }
        }
    }
}

function vypisZboziUprava() {
    $db = spojeni();

    if (isset($_GET['r'])) {
        if (!preg_match("/^[a-z]+$/", $_GET['r'])) {
            $razeni = "nazev";
            $zpusob="ASC";
        } else {
            $radit = $_GET['r'];
            if ($radit == "n") {
                $razeni = "nazev";
                $zpusob="ASC";
            }elseif ($radit == "m") {
                $razeni = "mnozstvi";
                $zpusob="ASC";
            }elseif($radit == "c"){
                $razeni = "cena";
                $zpusob="ASC";
            }elseif($radit == "p"){
                $razeni = "platnost";
                $zpusob="DESC";
            }else{
                $razeni = "nazev";
                $zpusob="ASC";
            }
        }
    } else {
        $razeni = "nazev";
        $zpusob="ASC";
    }

    $sql = "SELECT `zbozi`.`idzbozi` AS id, `zbozi`.`nazev`, (SELECT cena.cena FROM cena WHERE zbozi_id=`zbozi`.`idzbozi` ORDER BY cena.datum DESC LIMIT 1) AS cena , `zbozi`.`mnozstvi`,`zbozi`.`platnost`
FROM `zbozi` JOIN `cena` ON `zbozi`.`idzbozi`= `cena`.`zbozi_id` GROUP BY `zbozi`.`nazev` ORDER BY $razeni $zpusob";


    if ($data = $db->query($sql)) {
        if ($data->num_rows > 0) {
            echo "<div class='obalTable'><table class='table1'>";
            echo "<tr> <th><a href='upravaZbozi.php?r=n'>Název</a></th> <th><a href='upravaZbozi.php?r=c'>Cena</a></th>
    <th><a href='upravaZbozi.php?r=m'>Množství</a></th><th><a href='upravaZbozi.php?r=p'>Platnost</a></th> <th></th> <th></th></tr>";

            while ($row = $data->fetch_assoc()) {

                $sqlCena = "SELECT SUM(`objednavky`.`mnozstvi`) AS rez
                            FROM `objednavky` JOIN `faktury` ON `objednavky`.`faktury_id`=faktury.id JOIN `cena` ON `cena`.`id`=`objednavky`.cena_id
                            WHERE `faktury`.`datum_vydani` IS NULL AND `cena`.`zbozi_id`={$row['id']}";
                if ($dataR = $db->query($sqlCena)) {
                    if ($dataR->num_rows > 0) {
                        $rowR = $dataR->fetch_assoc();
                        $rez = $rowR['rez'];
                    }
                }

                if($rez == ''){
                    $rez = 0;
                }

                echo "<tr><td><a href='zboziEditace.php?id={$row['id']}'>{$row['nazev']}</a></td>  <td>{$row['cena']}</td> <td>{$row['mnozstvi']} ({$rez} rezervovano)</td> ";

                if($row['platnost']==1){
                    echo "<td>Platné</td><td><a href='upravaZbozi.php?sm=true&id={$row['id']}'>Smazat</a></td>";
                }else{
                    echo "<td>Neplatné</td><td><a href='upravaZbozi.php?ob=true&id={$row['id']}'>Obnov</a></td>";
                }


                echo "<td><a href='mnozstvi.php?id={$row['id']}'>Mnozstvi</a></td>
                      </tr>";

            }


            echo "</table></div>";
        } else
            echo "<p class='hlaska'>Omlouváme se, ale chybí nám tu zbozi.</p>";
    }

}

function vypisKategorie(){
    $db= spojeni();
    $sql = "SELECT * FROM `kategorie` ORDER BY `nazev` ASC";
    if($data = $db->query($sql)){
        if($data->num_rows > 0)
        {
            echo"<br>";
            echo "<select name='id_kat' class='select'>";
            while($row = $data->fetch_assoc())
            {
                echo "<option value='$row[idkategorie]'>$row[nazev]</option>";
            }
            echo "</select>";
        } else
            echo "Nejsou tu zadne kategorie";
    }
}

function vypisKategorieUprava() {
    $db = spojeni();


    $sql = "SELECT * FROM `kategorie` ORDER BY `nazev` ASC";


    if ($data = $db->query($sql)) {
        if ($data->num_rows > 0) {
            echo "<div class='obalTable'><table class='table1'>";
            echo "<tr> <th>Název</th><th>Množství výskytů</th><th></th> <th></th></tr>";

            while ($row = $data->fetch_assoc()) {
                $rez = 0;
                $sqlCena = "SELECT COUNT(`zbozi`.`idzbozi`) AS zb
                            FROM `zbozi` WHERE `zbozi`.`kategorie_id` ={$row['idkategorie']}";
                if ($dataR = $db->query($sqlCena)) {
                    if ($dataR->num_rows > 0) {
                        $rowR = $dataR->fetch_assoc();
                        $rez = $rowR['zb'];
                    }
                }

                //if($rez == ''){
                //    $rez = 0;
                //}

                echo "<tr><td>{$row['nazev']}</td> <td>{$rez}</td> <td><a href='editaceKategorie.php?id={$row['idkategorie']}'>Editace</a></td>";
                if($rez==0){echo "<td><a href='pridaniInfo.php?smk=true&id={$row['idkategorie']}'>Smaž</a></td>"; }else{echo "<td>Smaž</td>";}

                echo "</tr>";

            }


            echo "</table></div>";
        } else
            echo "<p class='hlaska'>Omlouváme se, ale chybí nám tu zbozi.</p>";
    }

}

function vypisVyrobce(){
    $db= spojeni();
    $sql = "SELECT * FROM `vyrobce` ORDER BY `nazev` ASC";
    if($data = $db->query($sql)){
        if($data->num_rows > 0)
        {
            echo"<br>";
            echo "<select name='id_vyr' class='select'>";
            while($row = $data->fetch_assoc())
            {
                echo "<option value='$row[idvyrobce]'>$row[nazev]</option>";
            }
            echo "</select>";
        } else
            echo "Nejsou tu zadne kategorie";
    }
}

function vypisVyrobceUprava() {
    $db = spojeni();


    $sql = "SELECT * FROM `vyrobce` ORDER BY `nazev` ASC";


    if ($data = $db->query($sql)) {
        if ($data->num_rows > 0) {
            echo "<div class='obalTable'><table class='table1'>";
            echo "<tr> <th>Název</th><th>ICO</th><th>Město, Adresa</th><th>Množství výskytů</th><th></th> <th></th></tr>";

            while ($row = $data->fetch_assoc()) {
                $rez = 0;
                $sqlCena = "SELECT COUNT(`zbozi`.`idzbozi`) AS zb
                            FROM `zbozi` WHERE `zbozi`.`vyrobce_id` ={$row['idvyrobce']}";
                if ($dataR = $db->query($sqlCena)) {
                    if ($dataR->num_rows > 0) {
                        $rowR = $dataR->fetch_assoc();
                        $rez = $rowR['zb'];
                    }
                }

                //if($rez == ''){
                //    $rez = 0;
                //}

                echo "<tr><td>{$row['nazev']}</td><td>{$row['ico']}</td><td>{$row['mesto']}, {$row['adresa']}</td> <td>{$rez}</td> <td><a href='editaceVyrobci.php?id={$row['idvyrobce']}'>Editace</a></td>";
                if($rez==0){echo "<td><a href='pridaniInfo.php?smv=true&id={$row['idvyrobce']}'>Smaž</a></td>"; }else{echo "<td>Smaž</td>";}

                echo "</tr>";

            }


            echo "</table></div>";
        } else
            echo "<p class='hlaska'>Omlouváme se, ale chybí nám tu zbozi.</p>";
    }

}

function nahledZbozi()
{
    $db = spojeni();
    if (isset($_GET['id'])) {
        if (!preg_match("/^[0-9]+$/", $_GET['id'])) {
            return;
        }

        $sql = "SELECT `zbozi`.`idzbozi` AS id,`zbozi`.`nazev`, (SELECT cena.cena FROM cena WHERE zbozi_id=`zbozi`.`idzbozi` ORDER BY cena.datum DESC LIMIT 1) AS cena , `zbozi`.`mnozstvi`, `zbozi`.popis
            FROM `zbozi` JOIN `cena` ON `zbozi`.`idzbozi`= `cena`.`zbozi_id` WHERE `zbozi`.`idzbozi`={$_GET['id']} GROUP BY `zbozi`.`nazev`";


        if ($data = $db->query($sql)) {
            if ($data->num_rows > 0) {

                while ($row = $data->fetch_assoc()) {
                    echo "<div class='nahledZbozi'>";
                    echo "<div class='fotoNahled'>";

                    $nazevSlozky = "images/obrazkyZbozi/" . $row['id'];
                    if (is_dir($nazevSlozky)) {
                        $slozka = opendir($nazevSlozky);
                        for ($i = 0; $i < 1; $i++) {
                            $files = scandir($nazevSlozky);
                            $pocetSoub = count($files);
                            while ($nazevSouboru = readdir($slozka)) {
                                if ($nazevSouboru != "." && $nazevSouboru != ".." && $nazevSouboru != "nahledy" && $nazevSouboru != "thumbs.db" && $nazevSouboru != "Thumbs.db") {

                                    echo "<img src='$nazevSlozky/$nazevSouboru' alt='' class='zkft'>";
                                    break;
                                } else if ($pocetSoub <= 2) {

                                    $odkaz = "images/1.jpg";
                                    echo "<img src='$odkaz' alt='' class='zkft'>";
                                    break;
                                }
                            }
                        }
                    }


                    echo "</div>";
                    $sqlCena = "SELECT SUM(`objednavky`.`mnozstvi`) AS rez
                            FROM `objednavky` JOIN `faktury` ON `objednavky`.`faktury_id`=faktury.id JOIN `cena` ON `cena`.`id`=`objednavky`.cena_id
                            WHERE `faktury`.`datum_vydani` IS NULL AND `cena`.`zbozi_id`={$row['id']}";
                    if ($dataR = $db->query($sqlCena)) {
                        if ($dataR->num_rows > 0) {
                            $rowR = $dataR->fetch_assoc();
                            $mnozRez = $row['mnozstvi']-$rowR['rez'];

                        }
                    }

                    echo " <div>
                <h1 class='name'>{$row['nazev']}</h1>
                <h1 class='name1'>Cena: {$row['cena']} Kč</h1>
                <h1 class='name1'>Množství: {$mnozRez} kusů</h1>";


                    echo "<p class='popisZbozi'>";
                    echo $row['popis'];
                   echo "</p>";
//                                }

                    echo "</div>";
                    echo "</div>";
                }
                echo "</div>";
            }
        }

    }
}

function rezervace() {
    $db = spojeni();
    if (isset($_POST["sended"])) {
        if (empty($_POST["mnozstvi"])) {
            echo "<p class='hlaska'>Vyplň formulář</p>";
        } else {
            if (!preg_match("/^[0-9]+$/", $_GET['id'])) {
                return;
            }
            $id = $_GET['id'];
            $mn = $_POST["mnozstvi"];


            $sql = "SELECT SUM(`objednavky`.`mnozstvi`) AS rez, `zbozi`.`mnozstvi`
FROM `objednavky` JOIN `faktury` ON `objednavky`.`faktury_id`=faktury.id JOIN `cena` ON `cena`.`id`=`objednavky`.cena_id JOIN zbozi ON zbozi.idzbozi=cena.zbozi_id
 WHERE `faktury`.`datum_vydani` IS NULL AND `cena`.`zbozi_id`={$id}";


            if ($data = $db->query($sql)) {
                if ($data->num_rows > 0) {

                    $row = $data->fetch_assoc();
                    $mnozRez = $row['mnozstvi'] - $row['rez'];
                    if ($mnozRez >= $mn) {
                        if (!isset($_SESSION['cart'])) {
                            $_SESSION['cart'] = array();
                        }

                        if (array_key_exists($id, $_SESSION['cart'])) {
                            $_SESSION['cart'][$id]['quantity'] = $mn;
                        } else {
                            $_SESSION['cart'][$id];
                            $_SESSION['cart'][$id]['quantity'] = $mn;
                        }

                        header("Location:zbozi.php");
                    } else {
                        echo "<h1 class='nadpis_vedlejsi_stranka'>Zadané množství je velké.</h1>";
                    }
                }
            }


        }
    }
}

function vypisRezervaci($op) {
    $db = spojeni();


if ($op == -1) {
    $sql = "SELECT `faktury`.`id`, `faktury`.`datum_vytvoreni`, `uzivatel`.`jmeno`, `uzivatel`.`prijmeni`, SUM(`cena`.`cena`) AS cena
FROM `faktury`JOIN `objednavky` ON faktury.id=objednavky.faktury_id JOIN `cena` ON objednavky.cena_id=cena.id JOIN `uzivatel` ON `uzivatel`.`id`=`faktury`.`uzivatel_id`
WHERE `faktury`.`datum_vydani` IS NULL
GROUP BY `faktury`.`id`";
} else{
    $sql = "SELECT `faktury`.`id`, `faktury`.`datum_vytvoreni`, `uzivatel`.`jmeno`, `uzivatel`.`prijmeni`, SUM(`cena`.`cena`) AS cena
FROM `faktury`JOIN `objednavky` ON faktury.id=objednavky.faktury_id JOIN `cena` ON objednavky.cena_id=cena.id JOIN `uzivatel` ON `uzivatel`.`id`=`faktury`.`uzivatel_id`
WHERE `faktury`.`datum_vydani` IS NULL AND `uzivatel`.`id`={$op}
GROUP BY `faktury`.`id`";

}

    if ($data = $db->query($sql)) {
        if ($data->num_rows > 0) {
            echo "<div class='obalTable'><table class='table1'>";
            echo "<tr> <th>Číslo faktury</th> <th>Datum vytvoreni</th><th>Uživatel</th> <th>Cena</th> <th></th><th></th></tr>";

            while ($row = $data->fetch_assoc()) {
                $sqlC = "SELECT `objednavky`.`mnozstvi`, `cena`.`cena` FROM `objednavky` JOIN `cena` ON objednavky.cena_id=cena.id WHERE objednavky.faktury_id={$row['id']}";
                $cenaCelkem =0;
                if ($dataC = $db->query($sqlC)) {
                    if ($dataC->num_rows > 0) {
                       while ($rowC = $dataC->fetch_assoc()) {
                            $cenaCelkem = $cenaCelkem +($rowC['mnozstvi']*$rowC['cena']);
                        }
                    }
                }


                echo "<tr><td>{$row['id']}</td>  <td>{$row['datum_vytvoreni']}</td> <td>{$row['jmeno']} {$row['prijmeni']}</td> <td>{$cenaCelkem} Kc</td>
                        <td><a href='podrobnosti.php?id={$row['id']}'>Podrobnosti</a></td> ";
                if ($op == -1) {echo "<td><a href='rezervace.php?vd=true&id={$row['id']}'>Vydat</a> <a href='rezervace.php?zr=true&id={$row['id']}'>Zrušit</a></td>";}
                else{echo "<td><a href='rezervace.php?zr=true&id={$row['id']}'>Zrušit</a></td>";}
                echo"</tr>";

            }


            echo "</table></div>";
        } else
            echo "<h1 class='nadpis_vedlejsi_stranka'>Žádné rezervace</h1>";
    }

}

function vypisRezervaciPodrobnosti() {
    $db = spojeni();

    if (isset($_GET['id'])) {
        if (!preg_match("/^[0-9]+$/", $_GET['id'])) {
            return;
        }


        $sql = "SELECT `zbozi`.`nazev`, `objednavky`.`mnozstvi`, `vyrobce`.`nazev` AS vyrobce
FROM `objednavky` JOIN `cena` ON objednavky.cena_id=cena.id JOIN `zbozi` ON zbozi.idzbozi=cena.zbozi_id JOIN `vyrobce` ON zbozi.vyrobce_id=vyrobce.idvyrobce
WHERE objednavky.faktury_id={$_GET['id']}";


        if ($data = $db->query($sql)) {
            if ($data->num_rows > 0) {
                echo "<div class='obalTable'><table class='table1'>";
                echo "<tr> <th>Výrobce</th> <th>Název produktu</th><th>Množství</th></tr>";

                while ($row = $data->fetch_assoc()) {

                    echo "<tr><td>{$row['vyrobce']}</td>  <td>{$row['nazev']}</td> <td>{$row['mnozstvi']}</td></tr>";

                }


                echo "</table></div>";
            } else
                echo "<h1 class='nadpis_vedlejsi_stranka'>Žádné rezervace</h1>";
        }
    }
}

function vydatZbozi()
{
    $db = spojeni();

    if (isset($_GET['id'])) {
        if (!preg_match("/^[0-9]+$/", $_GET['id'])) {
            return;
        }

        $sql = "UPDATE `faktury` SET `datum_vydani` = CURRENT_TIMESTAMP WHERE `faktury`.`id`= {$_GET['id']}";
        if ($stmt = $db->prepare($sql)) {
            $stmt->execute();
        } else {
            echo "CHYBA";
        }

        $sql = "SELECT `zbozi`.`idzbozi`, `zbozi`.`mnozstvi` AS mn, `objednavky`.`mnozstvi` AS obj
FROM `objednavky` JOIN `cena` ON objednavky.cena_id=cena.id JOIN `zbozi` ON zbozi.idzbozi=cena.zbozi_id JOIN `vyrobce` ON zbozi.vyrobce_id=vyrobce.idvyrobce
WHERE objednavky.faktury_id={$_GET['id']}";


        if ($data = $db->query($sql)) {
            if ($data->num_rows > 0) {
                while ($row = $data->fetch_assoc()) {
                    $noveMnozstvi = $row['mn'] - $row['obj'];
                    $sqlU = "UPDATE `zbozi` SET `mnozstvi` = ? WHERE `zbozi`.`idzbozi` = ?";
                    if ($stmt = $db->prepare($sqlU)) {
                        $stmt->bind_param("ii",$noveMnozstvi ,$row['idzbozi']);
                        $stmt->execute();
                    } else {
                        echo "CHYBA";
                    }
                }
                header("Location:rezervace.php");
            }
        }
    }
}

function zrusitRezervaciZbozi()
{
    $db = spojeni();

    if (isset($_GET['id'])) {
        if (!preg_match("/^[0-9]+$/", $_GET['id'])) {
            return;
        }

        $sql = "DELETE FROM `objednavky` WHERE `objednavky`.`faktury_id` = {$_GET['id']}";
        if ($stmt = $db->prepare($sql)) {
            $stmt->execute();
        } else {
            echo "CHYBA";
        }

        $sql = "DELETE FROM `faktury` WHERE `faktury`.`id`= {$_GET['id']}";
        if ($stmt = $db->prepare($sql)) {
            $stmt->execute();
        } else {
            echo "CHYBA";
        }


        header("Location:rezervace.php");

    }
}

function vypisHistorieRezervaci($op) {
    $db = spojeni();

if ($op == -1){
    $sql = "SELECT `faktury`.`id`, `faktury`.`datum_vytvoreni`, `faktury`.`datum_vydani`, `uzivatel`.`jmeno`, `uzivatel`.`prijmeni`, SUM(`cena`.`cena`) AS cena
FROM `faktury`JOIN `objednavky` ON faktury.id=objednavky.faktury_id JOIN `cena` ON objednavky.cena_id=cena.id JOIN `uzivatel` ON `uzivatel`.`id`=`faktury`.`uzivatel_id`
WHERE `faktury`.`datum_vydani` IS NOT NULL
GROUP BY `faktury`.`id`";
}else{
    $sql = "SELECT `faktury`.`id`, `faktury`.`datum_vytvoreni`, `faktury`.`datum_vydani`, `uzivatel`.`jmeno`, `uzivatel`.`prijmeni`, SUM(`cena`.`cena`) AS cena
FROM `faktury`JOIN `objednavky` ON faktury.id=objednavky.faktury_id JOIN `cena` ON objednavky.cena_id=cena.id JOIN `uzivatel` ON `uzivatel`.`id`=`faktury`.`uzivatel_id`
WHERE `faktury`.`datum_vydani` IS NOT NULL AND `uzivatel`.`id`={$op}
GROUP BY `faktury`.`id`";
}




    if ($data = $db->query($sql)) {
        if ($data->num_rows > 0) {
            echo "<div class='obalTable'><table class='table1'>";
            echo "<tr> <th>Číslo faktury</th> <th>Datum vytvoreni</th> <th>Datum vydání</th><th>Uživatel</th> <th>Cena</th> <th></th></tr>";

            while ($row = $data->fetch_assoc()) {
                $sqlC = "SELECT `objednavky`.`mnozstvi`, `cena`.`cena` FROM `objednavky` JOIN `cena` ON objednavky.cena_id=cena.id WHERE objednavky.faktury_id={$row['id']}";
                $cenaCelkem =0;
                if ($dataC = $db->query($sqlC)) {
                    if ($dataC->num_rows > 0) {
                        while ($rowC = $dataC->fetch_assoc()) {
                            $cenaCelkem = $cenaCelkem +($rowC['mnozstvi']*$rowC['cena']);
                        }
                    }
                }


                echo "<tr><td>{$row['id']}</td>  <td>{$row['datum_vytvoreni']}</td> <td>{$row['datum_vydani']}</td> <td>{$row['jmeno']} {$row['prijmeni']}</td> <td>{$cenaCelkem} Kc</td>
                        <td><a href='podrobnosti.php?id={$row['id']}'>Podrobnosti</a></td>
                      </tr>";

            }


            echo "</table></div>";
        } else
            echo "<h1 class='nadpis_vedlejsi_stranka'>Žádné historie</h1>";
    }

}

function pridatKategorie() {
    $db = spojeni();
    if (isset($_POST["sendedK"])) {
        if (empty($_POST["nazev"])) {
            echo "<p class='hlaska'>Vyplň formulář</p>";
        } else {
            $nazev = $_POST["nazev"];


            $sql5 = "INSERT INTO `kategorie` (`nazev`) VALUES (?);";
            if ($stmt = $db->prepare($sql5)) {
                $stmt->bind_param("s", $nazev);
                $stmt->execute();

                echo "<p class='hlaska'>Kategorie vlozena.</p>";
            }else {
                echo "<p class='hlaska'>Chyba</p>";
            }
        }
    }
}

function editKategorie() {
    $db = spojeni();
    if (isset($_POST["sendedK"]) && isset($_GET['id'])) {
        if (empty($_POST["nazev"])) {
            echo "<p class='hlaska'>Vyplň formulář</p>";
        } else {
            if (!preg_match("/^[0-9]+$/", $_GET['id'])) {
                return;
            }
            $nazev = $_POST["nazev"];


            $sql5 = "UPDATE `kategorie` SET `nazev` = ? WHERE `kategorie`.`idkategorie` = {$_GET['id']}";
            if ($stmt = $db->prepare($sql5)) {
                $stmt->bind_param("s", $nazev);
                $stmt->execute();

                header("Location:pridaniInfo.php");
            }else {
                echo "<p class='hlaska'>Chyba</p>";
            }
        }
    }
}

function pridatVyrobce() {
    $db = spojeni();
    if (isset($_POST["sendedV"])) {
        if (empty($_POST["nazev"]) || empty($_POST["mesto"]) || empty($_POST["adresa"]) || empty($_POST["ico"])) {
            echo "<p class='hlaska'>Vyplň formulář</p>";
        } else {
            $nazev = $_POST["nazev"];
            $mesto = $_POST["mesto"];
            $adresa = $_POST["adresa"];
            $ico = $_POST["ico"];


            $sql5 = "INSERT INTO `vyrobce` (`nazev`, `mesto`, `adresa`, `ico`) VALUES (?,?,?,?);";
            if ($stmt = $db->prepare($sql5)) {
                $stmt->bind_param("sssi", $nazev, $mesto, $adresa, $ico);
                $stmt->execute();

                echo "<p class='hlaska'>Výrobce vlozen.</p>";
            }else {
                echo "<p class='hlaska'>Chyba</p>";
            }

        }
    }
}

function editVyrobce() {
    $db = spojeni();
    if (isset($_POST["sendedV"]) && isset($_GET['id'])) {
        if (empty($_POST["nazev"]) || empty($_POST["mesto"]) || empty($_POST["adresa"]) || empty($_POST["ico"])) {
            echo "<p class='hlaska'>Vyplň formulář</p>";
        } else {
            if (!preg_match("/^[0-9]+$/", $_GET['id'])) {
                return;
            }
            $nazev = $_POST["nazev"];
            $mesto = $_POST["mesto"];
            $adresa = $_POST["adresa"];
            $ico = $_POST["ico"];


            $sql5 = "UPDATE `vyrobce` SET `nazev` = ?, `mesto` = ?, `adresa` = ?, `ico` = ? WHERE `vyrobce`.`idvyrobce` = {$_GET['id']}";
            if ($stmt = $db->prepare($sql5)) {
                $stmt->bind_param("sssi", $nazev, $mesto, $adresa, $ico);
                $stmt->execute();

                header("Location:pridaniInfo.php");
            }else {
                echo "<p class='hlaska'>Chyba</p>";
            }

        }
    }
}

function smazVyrobce(){
    $db = spojeni();

    if (isset($_GET['id'])) {
        if (!preg_match("/^[0-9]+$/", $_GET['id'])) {
            return;
        }

        $sql = "DELETE FROM `vyrobce` WHERE idvyrobce= {$_GET['id']}";
        if ($stmt = $db->prepare($sql)) {
            $stmt->execute();
        } else {
            echo "CHYBA";
        }


        header("Location:pridaniInfo.php");

    }
}

function smazKategorie(){
    $db = spojeni();

    if (isset($_GET['id'])) {
        if (!preg_match("/^[0-9]+$/", $_GET['id'])) {
            return;
        }

        $sql = "DELETE FROM `kategorie` WHERE idkategorie= {$_GET['id']}";
        if ($stmt = $db->prepare($sql)) {
            $stmt->execute();
        } else {
            echo "CHYBA";
        }


        header("Location:pridaniInfo.php");

    }
}

function smazZbozi(){
    $db = spojeni();

    if (isset($_GET['id'])) {
        if (!preg_match("/^[0-9]+$/", $_GET['id'])) {
            return;
        }

        $sql = "UPDATE `zbozi` SET `platnost` = 0 WHERE `zbozi`.`idzbozi`= {$_GET['id']}";
        if ($stmt = $db->prepare($sql)) {
            $stmt->execute();
            header("Location:upravaZbozi.php");
        } else {
            echo "CHYBA";
        }
    }
}

function obnovZbozi(){
    $db = spojeni();

    if (isset($_GET['id'])) {
        if (!preg_match("/^[0-9]+$/", $_GET['id'])) {
            return;
        }

        $sql = "UPDATE `zbozi` SET `platnost` = 1 WHERE `zbozi`.`idzbozi`= {$_GET['id']}";
        if ($stmt = $db->prepare($sql)) {
            $stmt->execute();
            header("Location:upravaZbozi.php");
        } else {
            echo "CHYBA";
        }
    }
}

function vypisZboziSmazane(){
    $db = spojeni();

    if (isset($_GET['st'])) {
        if (!preg_match("/^[0-9]+$/", $_GET['st'])) {
            $strana = 1;
        } else {
            $strana = $_GET['st'];
        }
    } else {
        $strana = 1;
    }
    $naStranu = 6;
    $pocetStranData = "SELECT COUNT(*) FROM `zbozi` WHERE `zbozi`.`platnost`=0";
    $dataS = mysqli_query($db, $pocetStranData);
    $stran = mysqli_fetch_array($dataS);
    $pocet = ($strana - 1) * $naStranu;
    $pocetStran = ceil($stran[0] / $naStranu);
    if (isset($_GET['st'])) {
        if ($_GET['st'] > $pocetStran) {
            $strana = 1;
            $pocet = 1;
            $naStranu = 6;
        }
    }


    if (isset($_GET['r'])) {
        if (!preg_match("/^[a-z]+$/", $_GET['r'])) {
            $razeni = "id";
            $zpusob="DESC";
        } else {
            $radit = $_GET['r'];
            if ($radit == "abecedne") {
                $razeni = "nazev";
                $zpusob="ASC";
            }elseif ($radit == "nejlevnejsi") {
                $razeni = "cena";
                $zpusob="ASC";
            }elseif($radit == "nejdrazsi"){
                $razeni = "cena";
                $zpusob="DESC";
            }else{
                $razeni = "id";
                $zpusob="DESC";
            }
        }
    } else {
        $razeni = "id";
        $zpusob="DESC";
    }



    $sql = "SELECT `zbozi`.`idzbozi` AS id,`zbozi`.`nazev`, (SELECT cena.cena FROM cena WHERE zbozi_id=`zbozi`.`idzbozi` ORDER BY cena.datum DESC LIMIT 1) AS cena , `zbozi`.`mnozstvi`, `zbozi`.popis
            FROM `zbozi` JOIN `cena` ON `zbozi`.`idzbozi`= `cena`.`zbozi_id` WHERE `zbozi`.`platnost`=0 GROUP BY `zbozi`.`nazev` ORDER BY $razeni $zpusob LIMIT $pocet, $naStranu";

    echo "<div class='cl'>";
    if ($data = $db->query($sql)) {
        if ($data->num_rows > 0) {

            while ($row = $data->fetch_assoc()) {
                echo "<div class='article'>";
                echo "<div class='foto1'>";

                $nazevSlozky = "images/obrazkyZbozi/" . $row['id'];
                if (is_dir($nazevSlozky)) {
                    $slozka = opendir($nazevSlozky);
                    for ($i = 0; $i < 1; $i++) {
                        $files = scandir($nazevSlozky);
                        $pocetSoub = count($files);
                        while ($nazevSouboru = readdir($slozka)) {
                            if ($nazevSouboru != "." && $nazevSouboru != ".." && $nazevSouboru != "nahledy" && $nazevSouboru != "thumbs.db" && $nazevSouboru != "Thumbs.db") {

                                echo "<img src='$nazevSlozky/$nazevSouboru' alt='' class='zkft'>";
                                break;
                            } else if ($pocetSoub <= 2) {

                                $odkaz = "images/1.jpg";
                                echo "<img src='$odkaz' alt='' class='zkft'>";
                                break;
                            }
                        }
                    }
                }

                echo "</div>";

                /*if ($row['sleva'] != 0) {
                    $text = "– zlevněno " . $row['sleva'] . "%";
                    $cenaPred = ", (cena před " . $row['cena'] . " Kč)";
                    $cena = $row['cena'] - $row['cena'] / $row['sleva'];
                } else {
                    $text = "";
                    $cena = $row['cena'];
                    $cenaPred = "";
                }*/

                $sqlCena = "SELECT SUM(`objednavky`.`mnozstvi`) AS rez
                            FROM `objednavky` JOIN `faktury` ON `objednavky`.`faktury_id`=faktury.id JOIN `cena` ON `cena`.`id`=`objednavky`.cena_id
                            WHERE `faktury`.`datum_vydani` IS NULL AND `cena`.`zbozi_id`={$row['id']}";
                if ($dataR = $db->query($sqlCena)) {
                    if ($dataR->num_rows > 0) {
                        $rowR = $dataR->fetch_assoc();
                        $mnozRez = $row['mnozstvi']-$rowR['rez'];

                    }
                }

                echo" <div class='cln1'>
                                <h1 class='name'>{$row['nazev']}</h1>
                                <h1 class='name1'>Cena: {$row['cena']} Kč</h1>
                                <h1 class='name1'>Množství: {$mnozRez} kusů</h1>";

                //Oříznutí popisu
//                                $znak = strpos($row['popis'],'</p>');
//                                if ($znak<150) {
//                                echo substr($row['popis'], 0, $znak);
//                                echo "...";
//                                }else if(strlen($row['popis'])>=150){
//                                echo substr($row['popis'], 0, 150);
//                                echo "...";
//                                }else{
                echo"<p class='popisZbozi'>";
                echo $row['popis'];
                echo"</p>";
//                                }

                echo"</div>";
                /*if(!isset($_SESSION["login"])){
                    echo "<br><a href='#' onclick='alertJS();return false;' class='a'><button class='tlacitko1'>REZERVOVAT</button></a>"; //dát správný odkaz
                }else{
                    echo "<br><a href='#.php?id=$row[id]' class='a'><button class='tlacitko1'>REZERVOVAT</button></a>"; //dát správný odkaz
                }*/


                echo"</div>";
            }echo"</div>";

            echo"<div class='obalCisel'>";

            if (!isset($_GET["st"]))
                $i = 1;
            else {
                $i = $_GET['st'];
                if (!preg_match("/^[0-9]+$/", $_GET['st'])) {
                    $i = 1;
                }
            }
            if (isset($_GET['st'])) {
                if ($_GET['st'] > $pocetStran) {
                    $i = 1;
                }
            }

            if ($i > 1) {
                if(isset($_GET['r'])){
                    echo "<a href='zbozi.php?st=1&r=" . ($_GET['r']) . "'>&lt;&lt; </a>";
                }else {
                    echo "<a href='zbozi.php?st=1'>&lt;&lt; </a>";
                }
            }

// PŘEDCHOZÍ
            if ($i > 1) {
                if(isset($_GET['r'])){
                    echo "<a class='stranky'  href='zbozi.php?st=" . ($i - 1) . "&r=" . ($_GET['r']) . "'> &lt; </a>";
                }else {
                    echo "<a class='stranky'  href='zbozi.php?st=" . ($i - 1) . "'> &lt; </a>";
                }
// PŘEDCHOZÍ - CYKLUS
                for ($j = 4; $j > 0; $j--) {
                    if (($i - $j) >= 1) {
                        if(isset($_GET['r'])){
                            echo "<a class='stranky' href='zbozi.php?st=" . ($i - $j) . "&r=" . ($_GET['r']) . "'>" . ($i - $j) . "</a>";
                        }else{
                            echo "<a class='stranky' href='zbozi.php?st=" . ($i - $j) . "'>" . ($i - $j) . "</a>";
                        }
                    }
                }
            }

            echo "<b class='aktualStr'>" . $i . "</b>";

// DALŠÍ
            if ($i < ($pocetStran)) {
                // DALŠÍ - CYKLUS
                for ($m = 1; $m < 4; $m++) {
                    if (($i + $m) <= ceil($pocetStran)) {
                        if(isset($_GET['r'])){
                            echo "<a class='stranky' href='zbozi.php?st=" . ($i + $m) . "&r=" . ($_GET['r']) . "'>" . ($i + $m) . "</a>";
                        }else {
                            echo "<a class='stranky' href='zbozi.php?st=" . ($i + $m) . "'>" . ($i + $m) . "</a>";
                        }
                    }
                }

// DALŠÍ
                if(isset($_GET['r'])){
                    echo "<a class='stranky' href='zbozi.php?st=" . ($i + 1) . "&r=" . ($_GET['r']) . "'> &gt; </a>";
                }else {
                    echo "<a class='stranky' href='zbozi.php?st=" . ($i + 1) . "'> &gt; </a>";
                }

            }


// KONEC
            if ($i < ceil($pocetStran)) {
                if(isset($_GET['r'])){
                    echo "<a class='stranky' href='zbozi.php?st=" . ceil($pocetStran) . "&r=" . ($_GET['r']) . "'> &gt;&gt;</a>";
                }else{
                    echo "<a class='stranky' href='zbozi.php?st=" . ceil($pocetStran) . "'> &gt;&gt;</a>";
                }

            }

            echo "</div>";
        } else
            echo "<p class='hlaska'>Omlouváme se, ale chybí nám tu zbozi.</p>";
    }
}

function vypisUzivatelu() {
    $db = spojeni();


    $sql = "SELECT * FROM `uzivatel`";


    if ($data = $db->query($sql)) {
        if ($data->num_rows > 0) {
            echo "<div class='obalTable'><table class='table1'>";
            echo "<tr> <th>Jméno </th> <th>Přijmeni</th> <th>Email</th> <th>Platnost</th>  <th></th><th></th></tr>";

            while ($row = $data->fetch_assoc()) {


                echo "<tr><td>{$row['jmeno']}</td>  <td>{$row['prijmeni']}</td> <td>{$row['email']}</td> ";

                if($row['platnost']==1 && $row['opravneni']!=1){
                    echo "<td>Platné</td><td><a href='spravaUzivatel.php?sm=true&id={$row['id']}'>Smazat</a></td>";
                }else if($row['opravneni']!=1){
                    echo "<td>Neplatné</td><td><a href='spravaUzivatel.php?ob=true&id={$row['id']}'>Obnov</a></td>";
                }else{
                    echo "<td>Admin</td><td></td>";
                }
                if ($row['opravneni']!=1 || $row['id']==$_SESSION['id']){
                    echo "<td><a href='editaceUzivatel.php?id={$row['id']}'>Edit</a></td>";
                }else{
                    echo "<td>Nelze</td>";
                }

                echo "</tr>";

            }


            echo "</table></div>";
        } else
            echo "<p class='hlaska'>Omlouváme se, ale chybí nám tu zbozi.</p>";
    }

}


function editUdaju($id){
    $db = spojeni();
    if (isset($_POST["sendedD"])) {
        if (empty($_POST["jmeno"]) || empty($_POST["prijmeni"])) {
            echo "<p class='hlaska'>Vyplň formulář</p>";
        } else {
            $jmeno = $_POST["jmeno"];
            $prijmeni = $_POST["prijmeni"];


            $sql5 = "UPDATE `uzivatel` SET `jmeno` = ?, `prijmeni` = ? WHERE `uzivatel`.`id` = ?";
            if ($stmt = $db->prepare($sql5)) {
                $stmt->bind_param("ssi", $jmeno, $prijmeni,$id);
                $stmt->execute();

            }
            if ($_SESSION['opravneni']==1){
                header("Location:spravaUzivatel.php");
            }else{
                header("Location:index.php");
            }
        }
    }
}

function editHesla($id){
    $db = spojeni();
    if (isset($_POST["sendedH"])) {
        if (empty($_POST["heslo1"]) || empty($_POST["heslo2"])) {
            echo "<p class='hlaska'>Vyplň formulář</p>";
        } else if ($_POST["heslo1"] != $_POST["heslo2"]) {
            echo "<p class='hlaska'>Hesla nejsou stejná.</p>";
        } else {
            $heslo = password_hash($_POST["heslo1"], PASSWORD_BCRYPT);


            $sql5 = "UPDATE `uzivatel` SET `heslo` = ? WHERE `uzivatel`.`id` = ?";
            if ($stmt = $db->prepare($sql5)) {
                $stmt->bind_param("si", $heslo, $id);
                $stmt->execute();
            }
            if ($_SESSION['opravneni']==1){
                header("Location:spravaUzivatel.php");
            }else{
                header("Location:odhlaseni.php");
            }
        }
    }
}
?>
<script>
    function alertJS() {
        if (confirm("Pro rezervaci se musíte příhlásit!\nPřihlásit se?")) {
            window.location.href = "prihlaseni.php";
        }
    }

</script>
