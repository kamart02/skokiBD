<!DOCTYPE html>

<?php include('../sql/dbLog.php'); ?>

<head>
    <?php include('../template/head.php') ?>
</head>

<?php include('../template/top.php'); ?>

    <div class="content flex-center">
        <div class="input-box">
            <h1>Wybierz zawodnika</h1>
            <form method="GET">
                <ul class="input-list">
                    <li>
                        <label for="iduczestnika">Imie i nazwisko</label>
                        <select name="iduczestnika">
                            <?php
                                $database = pg_connect($DBLOGINSTR);
                                if ($database == false) {
                                    die("Database error");
                                }
                                $querry = pg_query($database, "SELECT * FROM uczestnik");
                                if ($querry == false) {
                                    die("Database error");
                                }
                                while ($row = pg_fetch_array($querry)) {
                                    if (isset($_GET['iduczestnika']) && $_GET['iduczestnika'] == $row['iduczestnika']) {
                                        echo "<option value='" . $row['iduczestnika'] . "' selected>" . $row['imie'] . " " . $row['nazwisko'] . "</option>";
                                    } else {
                                        echo "<option value='" . $row['iduczestnika'] . "'>" . $row['imie'] . " " . $row['nazwisko'] . "</option>";
                                    }
                                }
                            ?>
                        </select>
                    </li>
                    <li class="flex-right submit">
                        <input type="submit" value="Wybierz"> 
                    </li>
                </ul>
            </form>
            
        </div>

    </div>
    <a href="/~ak438500/skokiBD/kibic/zawodnik.php?iduczestnika=<?php echo $_GET['iduczestnika'] ?>&order=dlugosc"><button>Sortuj po dlugosci</button></a>
    <a href="/~ak438500/skokiBD/kibic/zawodnik.php?iduczestnika=<?php echo $_GET['iduczestnika'] ?>&order=ocena"><button>Sortuj po ocenie</button></a>
    <a href="/~ak438500/skokiBD/kibic/zawodnik.php?iduczestnika=<?php echo $_GET['iduczestnika'] ?>&order=konkurs"><button>Sortuj po konkursie</button></a>
    <a href="/~ak438500/skokiBD/kibic/zawodnik.php?iduczestnika=<?php echo $_GET['iduczestnika'] ?>&order=data"><button>Sortuj po dacie</button></a>
    <table class="width100">
    <tr>
        <th class="width20">Konkurs</th>
        <th class="width20">Data</th>
        <th class="width20">Seria</th>
        <th class="width20">Długość</th>
        <th class="width20">Punkty</th>
    </tr>
    <?php
        function isInteger($input){
            return(ctype_digit(strval($input)));
        }

        $database = pg_connect($DBLOGINSTR);
        if ($database == false) {
            die("Database error");
        }

        if (isset($_GET['order'])) {
            if ($_GET['order'] == 'data') {
                $order = "konkurs.datawydarzenia DESC, seria.typserii";
            }
            else if ($_GET['order'] == 'konkurs') {
                $order = "konkurs.nazwa DESC, seria.typserii";
            }
            else if ($_GET['order'] == 'dlugosc') {
                $order = "skok.dlugosc DESC NULLS LAST, konkurs.datawydarzenia DESC, seria.typserii";
            }
            else if ($_GET['order'] == 'ocena') {
                $order = "skok.ocena DESC, konkurs.datawydarzenia DESC, seria.typserii";
            }
            else {
                $order = "konkurs.datawydarzenia DESC, seria.typserii";
            }
        } 
        else {
            $order = "konkurs.datawydarzenia DESC, seria.typserii";
        }

        $querry = pg_query_params($database, "SELECT * FROM skok, zgloszenie, uczestnik, seria, konkurs WHERE skok.idzgloszenia = zgloszenie.idzgloszenia AND zgloszenie.iduczestnika = uczestnik.iduczestnika AND skok.idserii = seria.idserii AND (seria.idserii = konkurs.seriakwalifikacyjna OR seria.idserii = konkurs.seriapierwsza OR seria.idserii = konkurs.seriadruga) AND uczestnik.iduczestnika = $1 ORDER BY " . $order , array($_GET['iduczestnika']));
        $numrows = pg_num_rows($querry);
        if ($numrows == 0) {
            echo "<p>Brak skoków skoczka w bazie danych</p>";
        } else {
            for ($i = 0; $i < $numrows; $i++) {
                echo "<tr>";
                $row = pg_fetch_array($querry);
                echo "<td class=\"width20\">" . $row['nazwa'] . " (" . $row['lokalizacja'] . ")" . "</td>";
                echo "<td class=\"width20\">" . $row['datawydarzenia'] . "</td>";
                echo "<td class=\"width20\">" . $row['typserii'] . "</td>";
                if (pg_field_is_null($querry, $i, 'dlugosc') == 0) {
                    echo "<td class=\"width20\">" . $row['dlugosc'] . "m</td>";
                } else {
                    if (pg_field_is_null($querry, $i, 'ocena') == 0) {
                        echo "<td class=\"width20\"> DNF</td>";
                    }
                    else {
                        echo "<td class=\"width20\"></td>";
                    }
                }
                echo "<td class=\"width20\">" . $row['ocena'] . "</td>";
                echo "</tr>";
            }
        }
        pg_close($database);
    ?>
</table>

<?php include('../template/bottom.php'); ?>