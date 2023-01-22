<!DOCTYPE html>

<?php include('adminTest.php'); ?>
<?php include('../sql/dbLog.php'); ?>

<head>
    <?php include('../template/head.php'); ?>
</head>

<?php include('../template/top.php'); ?>

<div class="content">
    <table class="width100">
        <tr>
            <th class="width20">Nazwa</th>
            <th class="width20">Lokalizacja</th>
            <th class="width20">Data</th>
            <th class="width40">Akcje</th>
        </tr>
        <?php
            function isInteger($input){
                return(ctype_digit(strval($input)));
            }

            $database = pg_connect($DBLOGINSTR);
            if ($database == false) {
                die("Database error");
            }

            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                if (!isInteger($_POST['idkonkursu'])) {
                    die("Nieprawidłowe dane POST");
                }
                $querry = pg_query_params($database, "SELECT istniejeKonkurs($1) AS wartosc", array($_POST['idkonkursu']));
                $querry = pg_fetch_row($querry);
                if ($querry[0] == false) {
                    die("Nieprawidłowe dane POST wartos");
                } else {
                    $querry = pg_query_params($database, "SELECT zamknietezgloszenia FROM konkurs WHERE idkonkursu=$1", array($_POST['idkonkursu']));
                    $querry = pg_fetch_array($querry);
                    if ($querry['zamknietezgloszenia'] == "t") {
                        $nowyBool = "f";
                    } else {
                        $nowyBool = "t";
                    }
                    pg_query_params($database, "UPDATE konkurs SET zamknietezgloszenia=$1 WHERE idkonkursu = $2", array($nowyBool, $_POST['idkonkursu']));
                }
            }

            $querry = pg_query($database, "SELECT * FROM Konkurs ORDER BY datawydarzenia DESC");
            $numrows = pg_num_rows($querry);
            if ($numrows == 0) {
                echo "<p>Brak konkursów w bazie danych</p>";
            } else {
                for ($i = 0; $i < $numrows; $i++) {
                    echo "<tr>";
                    $row = pg_fetch_array($querry);
                    echo "<td class=\"width20\">" . $row['nazwa'] . "</td>";
                    echo "<td class=\"width20\">" . $row['lokalizacja'] . "</td>";
                    echo "<td class=\"width20\">" . $row['datawydarzenia'] . "</td>";
                    echo "<td class=\"width60 flex-space-around\">";

                    if (pg_field_is_null($querry, $i, 'seriakwalifikacyjna') == 1 && pg_field_is_null($querry, $i, 'seriapierwsza') == 1 && pg_field_is_null($querry, $i, 'seriadruga') == 1) {
                        if ($row['zamknietezgloszenia'] == "f") {
                        echo "<form method=\"post\">
                                <button type=\"submit\" class=\"lightred\">Zamknij zgłoszenia</button>
                                <input type=\"hidden\" name=\"idkonkursu\" value=" . $row['idkonkursu'] . "></form>";
                        } else {
                            echo "<form method=\"post\">
                                <button type=\"submit\" class=\"lightgreen\">Otwórz zgłoszenia</button>
                                <input type=\"hidden\" name=\"idkonkursu\" value=" . $row['idkonkursu'] . "></form>";
                        }
                        echo "<a href=\"/~ak438500/skokiBD/admin/kwoty.php?idkonkursu=" .
                        $row['idkonkursu']
                        . "\"><button class=\"lightyellow\">Edytuj kwoty startowe</button></a>";

                        echo "<a href=\"/~ak438500/skokiBD/admin/rozpocznijKonkurs.php?idkonkursu=" .
                        $row['idkonkursu']
                        . "\"><button class=\"lightblue\">Rozpocznij zawody</button></a>";
                    } else {
                        echo "<a href=\"/~ak438500/skokiBD/admin/konkurs.php?idkonkursu=" .
                        $row['idkonkursu']
                        . "\"><button class=\"lightblue\">Edytuj wyniki</button></a>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            }
            pg_close($database);
        ?>
    </table>
    <a href="/~ak438500/skokiBD/admin/nowyKonkurs.php" class="button-a"><button class="newContest">Nowy konkurs</button></a>
</div>

<?php include('../template/bottom.php'); ?>