<!DOCTYPE html>

<?php include('../sql/dbLog.php'); ?>

<?php
    if (!isset($_GET['idkonkursu'])) {
        die("Nieprawidłowy request");
    }

?>

<head>
    <?php include('../template/head.php'); ?>
</head>

<?php include('../template/top.php'); ?>

<div class="content">
    <?php 
        $database = pg_connect($DBLOGINSTR);
        if ($database == false) {
            die("Database error");
        }

        $querry = pg_query_params($database, "SELECT * FROM konkurs WHERE idkonkursu=$1", array($_GET['idkonkursu']));
        $konkursRow = pg_fetch_assoc($querry);
    ?>
    <a href="/~ak438500/skokiBD/kibic/konkurs.php?idkonkursu=<?php echo $_GET['idkonkursu']?>"><button>Sortuj po numerze startowym</button></a>
    <a href="/~ak438500/skokiBD/kibic/konkurs.php?idkonkursu=<?php echo $_GET['idkonkursu']?>&order=nazwisko"><button>Sortuj po imieniu i nazwisku</button></a>
    <a href="/~ak438500/skokiBD/kibic/konkurs.php?idkonkursu=<?php echo $_GET['idkonkursu']?>&order=dlugosc"><button>Sortuj po dlugosci</button></a>
    <a href="/~ak438500/skokiBD/kibic/konkurs.php?idkonkursu=<?php echo $_GET['idkonkursu']?>&order=ocena"><button>Sortuj po ocenie</button></a>
    <h1>Konkurs <?php echo $konkursRow['nazwa']?></h1>

    <?php
        if (pg_field_is_null($querry, 0, "seriapierwsza") == 1 && pg_field_is_null($querry, 0, "seriakwalifikacyjna") == 1) {
            pg_close($database);
            echo "<div class='red flex-center'><h1>Konkurs nie został jeszcze rozpoczęty</h1></div>";
            return;
        }

        for ($seria = 0; $seria < 3; $seria++) {
            if ($seria == 0) {
                $typSerii = 'kwalifikacyjna';
                $poleSerii = 'seriakwalifikacyjna';
            } else if ($seria == 1) {
                $typSerii = 'pierwsza';
                $poleSerii = 'seriapierwsza';
            } else {
                $typSerii = 'druga';
                $poleSerii = 'seriadruga';
            }
            error_log($poleSerii);
            error_log($konkursRow[$poleSerii]);
            if (pg_field_is_null($querry, 0, $poleSerii) == 1) {
                continue;
            }

            $idSerii = $konkursRow[$poleSerii];

            echo "<h2>Seria " . $typSerii . "</h2>";

            echo "<table class='width100'>
            <tr>
                <th class='width20'>Numer Startowy</th>
                <th class='width20'>Imie</th>
                <th class='width20'>Nazwisko</th>
                <th class='width20'>Długość skoku</th>
                <th class='width20'>Ocena skoku</th>
            </tr>";
            error_log($_GET['order']);
            if (!isset($_GET['order'])){
                $order = 'numerstartowy';
            } else if ($_GET['order'] == 'nazwisko') {
                $order = 'nazwisko, imie, numerstartowy NULLS LAST';
            } else if ($_GET['order'] == 'dlugosc') {
                $order = 'dlugosc DESC NULLS LAST, numerstartowy';
            } else if ($_GET['order'] == 'ocena') {
                $order = 'ocena DESC, numerstartowy';
            } else {
                $order = 'numerstartowy';
            }

            error_log($order);

            $querryHelper = pg_query_params($database, "SELECT * FROM skok, zgloszenie, uczestnik WHERE skok.idserii=$1 AND skok.idzgloszenia=zgloszenie.idzgloszenia AND zgloszenie.iduczestnika=uczestnik.iduczestnika ORDER BY " . $order, array($idSerii));
            if($querryHelper == false) {
                die("Database error");
            }

            $liczbaSkokow = pg_num_rows($querryHelper);

            for ($i = 1; $i <= $liczbaSkokow; $i++) {
                $row = pg_fetch_assoc($querryHelper);
                echo "<tr>";
                echo "<td class='flex-center'>" . $row['numerstartowy'] . "</td>";
                echo "<td>" . $row['imie'] . "</td>";
                echo "<td>" . $row['nazwisko'] . "</td>";
                $value = pg_field_is_null($querryHelper, $i - 1, "dlugosc") == 1 && $row['ocena'] == 0 ? "DNF" : $row['dlugosc'] . "m";
                echo "<td>" .  $value . "</td>";
                $value = pg_field_is_null($querryHelper, $i - 1, "ocena") == 1 ? "" : $row['ocena'];
                echo "<td>" .  $value . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        }
        pg_close($database);
    ?>

    
</div>

<?php include('../template/bottom.php'); ?>