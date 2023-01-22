<!DOCTYPE html>

<body>
    <div class="nav">
        <div class="box left">
            <a class="brand" href="/~ak438500/skokiBD/info">
                <div class="brand-text">SkokiBD</div>
            </a>
            <a class="element" href="/~ak438500/skokiBD/info">Informacje</a>
            <a class="element" href="/~ak438500/skokiBD/kibic">Panel kibica</a>
            <a class="element" href="/~ak438500/skokiBD/admin">Panel administracyjny</a>
            <a class="element" href="/~ak438500/skokiBD/zawodnik">Panel zawodnika</a>
            <a class="element" href="/~ak438500/skokiBD/zawodnik/rejestracja.php?next=/~ak438500/skokiBD/zawodnik">Rejestracja zawodnika</a>
        </div>
        <div class="box right flex-center">
            <?php
                session_start();
                if (isset($_SESSION['loggedIn']) === true) {
                    echo "<p class = \"flex-self-center\">Zalogowany jako: " .  $_SESSION['username'] . "</p>";
                    echo "<a href = \"/~ak438500/skokiBD/login/logout.php\" class = \"button-a\"><button class = \"href-button lightgreen\">Wyloguj</button></a>";
                }
            ?>
        </div>
    </div>


