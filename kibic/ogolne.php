<!DOCTYPE html>

<?php include('../sql/dbLog.php'); ?>

<head>
    <?php include('../template/head.php'); ?>
</head>

<?php include('../template/top.php'); ?>

<div class="content">
    <a href="/~ak438500/skokiBD/kibic/ogolne.php?order=nazwisko"><button>Sortuj po imieniu i nazwisku</button></a>
    <a href="/~ak438500/skokiBD/kibic/ogolne.php?order=ocena"><button>Sortuj po sumie punktów</button></a>
    <table class="width100">
        <tr>
            <th class="width20">Pozycja</th>
            <th class="width20">Imie</th>
            <th class="width20">Nazwisko</th>
            <th class="width40">Suma punktów</th>
        </tr>
        <?php

            $database = pg_connect($DBLOGINSTR);
            if ($database == false) {
                die("Database error");
            }

            if (!isset($_GET['order'])){
                $order = 'suma DESC';
            } else if ($_GET['order'] == 'nazwisko') {
                $order = 'uczestnik.nazwisko, uczestnik.imie';
            } else {
                $order = 'suma DESC';
            }


            $querry = pg_query($database, "SELECT uczestnik.iduczestnika, uczestnik.imie, uczestnik.nazwisko, SUM(skok.ocena) as suma FROM skok, zgloszenie, uczestnik WHERE skok.idzgloszenia = zgloszenie.idzgloszenia AND zgloszenie.iduczestnika = uczestnik.iduczestnika GROUP BY uczestnik.iduczestnika ORDER BY " . $order);
            $numrows = pg_num_rows($querry);
            if ($numrows == 0) {
                echo "<p>Brak konkursów w bazie danych</p>";
            } else {
                for ($i = 0; $i < $numrows; $i++) {
                    echo "<tr>";
                    $row = pg_fetch_array($querry);
                    echo "<td class=\"width20\">" . ($i + 1) . "</td>";
                    echo "<td class=\"width20\">" . $row['imie'] . "</td>";
                    echo "<td class=\"width20\">" . $row['nazwisko'] . "</td>";
                    echo "<td class=\"width60 flex-space-around\">";

                    echo $row['suma'];

                    echo "</td>";
                    echo "</tr>";
                }
            }
            pg_close($database);
        ?>
    </table>
</div>

<?php include('../template/bottom.php'); ?>