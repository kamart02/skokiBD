<!DOCTYPE html>

<?php include('adminTest.php'); ?>
<?php include('../sql/dbLog.php'); ?>

<?php
    if (!isset($_GET['idkonkursu'])) {
        die("Nieprawidłowy request");
    }
    $database = pg_connect($DBLOGINSTR);
    if (!isset($_GET['seria'])) {
        $querry = pg_query_params($database, "SELECT * FROM konkurs WHERE idkonkursu=$1", array($_GET['idkonkursu']));
        if (pg_field_is_null($querry, 0, "seriapierwsza") == 1 && pg_field_is_null($querry, 0, "seriakwalifikacyjna") == 1) {
            pg_close($database);
            die("Nie rozpoczęto konkursu");
        }

        if (pg_field_is_null($querry, 0, "seriapierwsza") == 0) {
            pg_close($database);
            header("Location: konkurs.php?idkonkursu=" . $_GET['idkonkursu'] . "&seria=pierwsza");
        } else {
            pg_close($database);
            header("Location: konkurs.php?idkonkursu=" . $_GET['idkonkursu'] . "&seria=kwalifikacyjna");
        }
    }

    $database = pg_connect($DBLOGINSTR);
    if ($database == false) {
        die("Database error");
    }

    $poleSerii = 'seria' . $_GET['seria'];

    $konkurs = pg_query_params($database, "SELECT * FROM konkurs WHERE idkonkursu=$1", array($_GET['idkonkursu']));
    $konkursRow = pg_fetch_assoc($konkurs);

    if ($_GET['seria'] == 'koniec' || pg_field_is_null($konkurs, 0, $poleSerii) == 1 ) {
        if ($_GET['seria'] == 'pierwsza') {
            $idSerii = $konkursRow['seriakwalifikacyjna'];
        } else if ($_GET['seria'] == 'druga') {
            $idSerii = $konkursRow['seriapierwsza'];
        } else {
            $idSerii = $konkursRow['seriadruga'];
        }

        $skoki = pg_query_params($database, "SELECT * FROM skok, zgloszenie, uczestnik WHERE skok.idserii=$1 AND skok.idzgloszenia=zgloszenie.idzgloszenia AND zgloszenie.iduczestnika=uczestnik.iduczestnika", array($idSerii));
        $wszystkieSkoki = pg_fetch_all($skoki);

        $liczbaSkokow = pg_num_rows($skoki);

        $konkursZakonczony = true;
        for ($i = 0; $i < $liczbaSkokow; $i++) {
            if (pg_field_is_null($skoki, $i, 'ocena') == 1) {
                $konkursZakonczony = false;
                break;
            }
        }

        if (!$konkursZakonczony) {
            pg_close($database);
            if ($_GET['seria'] == 'pierwsza')
                header("Location: /~ak438500/skokiBD/admin/koniecKonkursuError.php?idkonkursu=" . $_GET['idkonkursu'] . "&seria=" . "kwaliifikacyjna");
            else if ($_GET['seria'] == 'druga')
                header("Location: /~ak438500/skokiBD/admin/koniecKonkursuError.php?idkonkursu=" . $_GET['idkonkursu'] . "&seria=" . "pierwsza");
            else if ($_GET['seria'] == 'koniec')
                header("Location: /~ak438500/skokiBD/admin/koniecKonkursuError.php?idkonkursu=" . $_GET['idkonkursu'] . "&seria=" . "druga");
            die("Nieprawidłowa kolejnośc serii");
        }

        if ($_GET['seria'] == 'koniec') {
            pg_close($database);
            header("Location: /~ak438500/skokiBD/admin");
            return;
        }

        header("Location: /~ak438500/skokiBD/admin/rozpocznijKonkurs.php?idkonkursu=" . $_GET['idkonkursu'] . "&seria=" . $_GET['seria']);
        return;
    }

?>

<head>
    <?php include('../template/head.php'); ?>
</head>

<?php include('../template/top.php'); ?>

<div class="content">
    <h1>Konkurs <?php echo $konkursRow['nazwa']?></h1>
    
    <?php
        if (pg_field_is_null($konkurs, 0, 'seriaKwalifikacyjna') == 0 && $_GET['seria'] != 'kwalifikacyjna') {
            echo "<a href='/~ak438500/skokiBD/admin/konkurs.php?idkonkursu=" . $_GET['idkonkursu'] . "&seria=kwalifikacyjna'><button>Seria kwalifikacyjna</button></a>";
        }
        if (pg_field_is_null($konkurs, 0, 'seriaPierwsza') == 0 && $_GET['seria'] != 'pierwsza') {
            echo "<a href='/~ak438500/skokiBD/admin/konkurs.php?idkonkursu=" . $_GET['idkonkursu'] . "&seria=pierwsza'><button>Seria pierwsza</button></a>";
        }
        if (pg_field_is_null($konkurs, 0, 'seriaDruga') == 0 && $_GET['seria'] != 'druga') {
            echo "<a href='/~ak438500/skokiBD/admin/konkurs.php?idkonkursu=" . $_GET['idkonkursu'] . "&seria=druga'><button>Seria druga</button></a>";
        } 
        if (pg_field_is_null($konkurs, 0, 'seriaPierwsza') == 1) {
            echo "<a href='/~ak438500/skokiBD/admin/konkurs.php?idkonkursu=" . $_GET['idkonkursu'] . "&seria=pierwsza'><button>Rozpocznij następną serie</button></a>";
        } else if (pg_field_is_null($konkurs, 0, 'seriaDruga') == 1) {
            echo "<a href='/~ak438500/skokiBD/admin/konkurs.php?idkonkursu=" . $_GET['idkonkursu'] . "&seria=druga'><button>Rozpocznij następną serie</button></a>";
        } else {
            echo "<a href='/~ak438500/skokiBD/admin/konkurs.php?idkonkursu=" . $_GET['idkonkursu'] . "&seria=koniec'><button>Zakończ konkurs</button></a>";
        }
    ?>

    <h2>Seria <?php echo $_GET['seria']; ?></h2>
    <form method="POST">
        <table class="width100">
            <tr>
                <th class="width20">Numer Startowy</th>
                <th class="width20">Imie i Nazwisko</th>
                <th class="width20">Długość skoku</th>
                <th class="width20">Ocena skoku</th>
                <th class="width20">Dyskwalifikacja</th>
            </tr>
            <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $database = pg_connect($DBLOGINSTR);
                    if ($database == false) {
                        die("Database error");
                    }

                    $liczbaSkokow = $_POST['liczbaSkokow'];
                    $idSerii = $_POST['idSerii'];

                    for ($i = 0; $i < $liczbaSkokow; $i++) {
                        $idSkoku = $_POST['idSkoku' . $i];
                        $dlugoscSkoku = $_POST['dlugosc' . $i];
                        $ocenaSkoku = $_POST['ocena' . $i];
                        if (isset($_POST['dyskwalifikacja' . $i])) {
                            $dyskwalifikacja = $_POST['dyskwalifikacja' . $i];
                            if ($dyskwalifikacja == 'on')
                                $dyskwalifikacja = 't';
                            else
                                $dyskwalifikacja = 'f';
                        } else {
                            $dyskwalifikacja = 'f';
                        }

                        if ($dyskwalifikacja == 't') {
                            $querry = pg_query_params($database, "UPDATE skok SET dlugosc=NULL, ocena=0, dyskwalifikacja=$1 WHERE idskoku=$2", array($dyskwalifikacja, $idSkoku));
                        } else {
                            if ($dlugoscSkoku == "" || $ocenaSkoku == "") {
                                continue;
                            }
                            $querry = pg_query_params($database, "UPDATE skok SET dlugosc=$1, ocena=$2, dyskwalifikacja=$3 WHERE idskoku=$4", array($dlugoscSkoku, $ocenaSkoku, $dyskwalifikacja, $idSkoku));
                        }

                        if($querry == false) {
                            die("Database error");
                        }
                    }
                }
            ?>

            <?php
                $database = pg_connect($DBLOGINSTR);
                if ($database == false) {
                    die("Database error");
                }

                $poleSerii = 'seria' . $_GET['seria'];


                $idSerii = $konkursRow[$poleSerii];

                $querry = pg_query_params($database, "SELECT * FROM skok, zgloszenie, uczestnik WHERE skok.idserii=$1 AND skok.idzgloszenia=zgloszenie.idzgloszenia AND zgloszenie.iduczestnika=uczestnik.iduczestnika ORDER BY numerstartowy", array($idSerii));
                if($querry == false) {
                    die("Database error");
                }

                $liczbaSkokow = pg_num_rows($querry);
                echo "<input type='hidden' name='liczbaSkokow' id='liczbaSkokow' value=" . $liczbaSkokow . ">";
                echo "<input type='hidden' name='idSerii' value=" . $idSerii . ">";

                for ($i = 1; $i <= $liczbaSkokow; $i++) {
                    $row = pg_fetch_assoc($querry);
                    echo "<tr>";
                    echo "<td class='flex-center'>" . $row['numerstartowy'] . "</td>";
                    echo "<td>" . $row['imie'] . " " . $row['nazwisko'] . "</td>";
                    $value = pg_field_is_null($querry, $i - 1, "dlugosc") == 1 ? "" : "value=" . $row['dlugosc'];
                    echo "<td>" . "<input id='dlugosc" . ($i - 1) . "' name='dlugosc" . ($i - 1) ."' type='number' min=0 max=500 step=0.1 " .  $value .">" . "</td>";
                    $value = pg_field_is_null($querry, $i - 1, "ocena") == 1 ? "" : "value=" . $row['ocena'];
                    echo "<td>" . "<input id='ocena" . ($i - 1) . "' name='ocena" . ($i - 1) ."' type='number' min=0 max=500 step=0.1 " .  $value .">" . "</td>";
                    $value = $row['dyskwalifikacja'] == 't' ? "checked" : "";
                    echo "<td>" . "<input id='dyskwalifikacja" . ($i - 1) . "' name='dyskwalifikacja" . ($i - 1) . "' type='checkbox' " . $value . ">" . "</td>";
                    echo "</tr>";
                    echo "<input type='hidden' name='idSkoku" . ($i - 1) . "' value=" . $row['idskoku'] . ">";
                }
                pg_close($database);
                ?>


            </table>
            <input type='submit' value='Zapisz zmiany'>
    </form>
</div>

<script src="/~ak438500/skokiBD/admin/autoDnf.js"></script>

<?php include('../template/bottom.php'); ?>