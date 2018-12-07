<?php
@session_start();
include "./funkce.php";
$db=spojeni();
Menu();
?>
<div class="pruh">
            <div class="skola">
                <div class="fotka"><img src="images/uvodni_foto.jpg" class="foto"></div>
                <div class="text1">
                    <p class="nadpis2">O NÁS</p>
                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Class aptent taciti sociosqu ad litora torquent per conubia nostra,
                    per inceptos hymenaeos. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam
                    rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Fusce wisi.
                    Maecenas sollicitudin. Fusce dui leo, imperdiet in, aliquam sit amet, feugiat eu, orci. Fusce suscipit libero eget elit. Nam
                    sed tellus id magna elementum tincidunt. Nunc auctor. Quisque tincidunt scelerisque libero.
                    <br><a href="#"><button class="tlacitko">ČÍST VÍCE</button></a>
                </div>
            </div>
        </div>
        
        <div id="baner2"></div>

        <div class="parent">
            <a href="registrace.php" >
                <div id="jedna" class="baby">
                    <img src="images/registrace.png" class="icon1" id="ikona">
                    <p class="text">REGISTRACE</p>
                </div></a>
            <a href="prihlaseni.php" >
                <div id="dva" class="baby">
                    <img src="images/login.png" class="icon2" id="ikona">
                    <p class="text">PŘIHLÁŠENÍ</p>
                </div></a>

            <a href="zbozi.php"><div id="tri" class="baby">
                    <img src="images/kosik.png" class="icon3" id="ikona">
                    <p class="text">NABÍDKA ZBOŽÍ</p>
                </div></a>

            <a href="#.php"><div id="ctyri" class="baby">
                    <img src="images/project.png" class="icon4" id="ikona">
                    <p class="text">ODKAZ 4</p>
                </div></a>

        </div>


        <div class="kontakt">
            <h1 class="nadpis_kontakt">KONTAKT</h1> 
            <div class="contact_div1">
          <p><b>Ředitelk:</b>  Jan Nekom</p>
          <p><b>Zástupce ředitele:</b>  Milan Kop</p>
          <p><b>IČO:</b>  00191086</p>
          <p><b>Adresa:</b></p>
          <p>Pardubice 500 71</p>

          
            </div>
            <div class="contact_div2">
                <p><b>Telefony:</b></p>
          <p>605 000 630, 722 172 660 - ŘEDITELKA</p>
          <p>605 000 650 - SKLAD</p>
          <p>744 000 302 - ZÁSTUPCE ŘEDITELE</p>
          <p><b>E-maily:</b></p>
          <p><a href="mailto:reditel@st.cz" class="mailto">reditel@st.cz</a></p>
          <p><a href="mailto:kancelar@st.cz" class="mailto">kancelar@st.cz</a></p></div>
            
            <div class="mapa">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1281.40088678855!2d15.766640422808036!3d50.03381009849956!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x470dcc95e3bfb037%3A0x5df5b1e51080fef2!2zbsOhbS4gxIxzLiBsZWdpw60sIDUzMCAwMiBQYXJkdWJpY2UgSQ!5e0!3m2!1scs!2scz!4v1542548497802" frameborder="0" style="border:0" allowfullscreen class="mapa_rozmery"></iframe>
            </div>
        </div> 
<?php
include './footer.php';
?>