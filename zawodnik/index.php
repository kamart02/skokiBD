<!DOCTYPE html>

<?php include('userTest.php'); ?>
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

            $querry = pg_query($database, "SELECT * FROM Konkurs ORDER BY datawydarzenia DESC");
            $numrows = pg_num_rows($querry);
            if ($numrows == 0) {
                echo "<p>Brak konkursów w bazie danych</p>";
            } else {
                for ($i = 0; $i < $numrows; $i++) {
                    echo "<tr>";
                    $row = pg_fetch_array($querry, $i);
                    if ($row == false) {
                        die("Database row error");
                    }
                    echo "<td class=\"width20\">" . $row['nazwa'] . "</td>";
                    echo "<td class=\"width20\">" . $row['lokalizacja'] . "</td>";
                    echo "<td class=\"width20\">" . $row['datawydarzenia'] . "</td>";
                    echo "<td class=\"width60 flex-space-around\">";
                    $helperQuerry = pg_query_params($database, "SELECT czyzgloszony($1, $2) AS wartosc", array($_SESSION['iduczestnika'], $row['idkonkursu']));
                    $helperRow = pg_fetch_array($helperQuerry);
                    
                    if ($helperRow['wartosc'] == "t") {
                        echo "Jesteś zgłoszony na konkurs";
                    } else if ($row['zamknietezgloszenia'] == "t") {
                        echo "Zgłoszenia zostały zamknięte";
                    } else {
                        echo "<a href=\"/~ak438500/skokiBD/zawodnik/zgloszenie.php?idKonkursu=" . $row['idkonkursu'] . "\"><button>Zgłoś się</button></a>";
                    }

                    echo "</tr>";
                }
            }
        ?>
    </table>
</div>

<?php include('../template/bottom.php'); ?>