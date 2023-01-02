<!DOCTYPE html>

<body>
    <div class="nav">
        <div class="box left">
            <a class="brand" href="/skokiBD/info">
                <div class="brand-text">SkokiBD</div>
            </a>
            <a class="element" href="/skokiBD/info">Informacje</a>
            <a class="element" href="../pKibica.html">Panel kibica</a>
            <a class="element" href="/skokiBD/admin">Panel administracyjny</a>
        </div>
        <div class="box right flex-center">
            <?php
                session_start();
                if (isset($_SESSION['loggedIn']) === true) {
                    echo "<p class = \"flex-self-center\">Zalogowany jako: " .  $_SESSION['username'] . "</p>";
                    echo "<a href = \"/skokiBD/login/logout.php\" class = \"button-a\"><button class = \"href-button lightgreen\">Wyloguj</button></a>";
                }
            ?>
        </div>
    </div>


