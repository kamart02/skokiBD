<!DOCTYPE html>

<?php include('adminTest.php'); ?>
<?php include('../sql/dbLog.php'); ?>

<?php 
    $database = pg_connect($DBLOGINSTR);
    if ($database == false) {
        die("Database error");
    }
    if (!isset($_GET['idkonkursu'])) {
        die("Nieprawidłowy request");
    }

    $konkurs = pg_query_params($database, "SELECT * FROM konkurs WHERE idkonkursu=$1", array($_GET['idkonkursu']));
    $konkursRow = pg_fetch_assoc($konkurs);

    if ($konkursRow['zamknietezgloszenia'] == 'f') {
        pg_close($database);
        header("Location: /~ak438500/skokiBD/admin/otwarteZgloszeniaError.php?next=/~ak438500/skokiBD/admin");
        return;
    }

    pg_close($database);
?>

<head>
    <?php include('../template/head.php'); ?>
</head>

<?php include('../template/top.php'); ?>

<div class="content flex-column">
<button id="newRep" class="lightgreen flex-self-center">Dodaj nową kwotę</button>
    <form method="post" class="flex-self-center">
        <table>
            <thead>
            <tr>
                <th>Kraj</th>
                <th>Kwota startowa</th>
            </tr>
            </thead>
            <tbody id = "formTable">
                <?php
                    $database = pg_connect($DBLOGINSTR);
                    if ($database == false) {
                        die("Database error");
                    }

                    if ($_SERVER['REQUEST_METHOD'] == "POST") {
                        $maxEntry = intval($_POST['maxEntry']);
                        for ($i = 0; $i < $maxEntry; $i++) {
                            $kraj = htmlspecialchars($_POST['kraj' . $i]);
                            if ($_POST['idReprezentacji' . $i] != "") {
                                pg_query_params($database,
                                    "UPDATE Reprezentacja SET kraj = $1, kwotaStartowa = $2 WHERE idReprezentacji = $3",
                                    array($kraj, $_POST['kwotaStartowa' . $i], $_POST['idReprezentacji' . $i]));
                            } else {
                                pg_query_params($database, 
                                    "INSERT INTO Reprezentacja(idKonkursu, kraj, kwotaStartowa) VALUES ($1, $2, $3)", 
                                    array($_POST['idKonkursu'], $kraj, $_POST['kwotaStartowa' . $i]));
                            }
                        }
                        
                        header("Location: /~ak438500/skokiBD/admin");

                    }

                    $querry = pg_query_params($database, "SELECT * FROM Reprezentacja WHERE idKonkursu = $1", array($_GET['idkonkursu']));
                    $numRows = pg_num_rows($querry);

                    echo "<input id = \"maxEntry\" type=\"hidden\" name=\"maxEntry\" value=\"". $numRows . "\">";
                    echo "<input type=\"hidden\" name=\"idKonkursu\" value=\"". $_GET['idkonkursu'] . "\">";

                    for ($i = 0; $i < $numRows; $i++) {
                        $row = pg_fetch_array($querry);
                        echo "<tr>";
                        echo "<td>";
                        echo "<input type=\"text\" name=\"kraj" . $i . "\" value=\"" . $row['kraj'] ."\" required>";
                        echo "</td>";
                        echo "<td>";
                        echo "<input type=\"number\" name=\"kwotaStartowa" . $i . "\" value=\"" . $row['kwotastartowa'] . "\" min=1 required>";
                        echo "</td>";
                        echo "<input type=\"hidden\" name=\"idReprezentacji" . $i . "\" value=\"". $row['idreprezentacji'] . "\">";

                        echo "</tr>";
                    }

                    pg_close($database);
                ?>
            </tbody>
        </table>
        
        <button class="lightred" type="submit">Zatwierdź</button>
    </form>
</div>


<script src="/~ak438500/skokiBD/admin/newRow.js"></script>
<?php include('../template/bottom.php'); ?>