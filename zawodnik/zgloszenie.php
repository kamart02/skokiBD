<!DOCTYPE html>

<?php include('userTest.php'); ?>
<?php include('../sql/dbLog.php'); ?>

<head>
    <?php include('../template/head.php'); ?>
</head>

<?php include('../template/top.php'); ?>

<div class="content">
    <div class="content flex-center">
        <?php
            $database = pg_connect($DBLOGINSTR);
            if ($database == false) {
                die("Database error");
            }

            $konkurs = pg_fetch_array(pg_query_params($database, "SELECT * FROM konkurs WHERE idkonkursu=$1", array($_GET['idKonkursu'])));
            $reprezentacje = pg_query_params($database, "SELECT * FROM reprezentacja WHERE idkonkursu=$1", array($_GET['idKonkursu']));
        ?>
        <div class="input-box">
            <h1>Zgłoszenie na konkurs <?php echo $konkurs['nazwa'] ?> </h1>
            <form method="POST">
                <ul class="input-list">
                    <li>
                        <label for="kraj">Kraj</label> 
                        <select name="kraj">
                            <option value="---">---</option>
                            <?php
                                for ($i = 0; $i < pg_num_rows($reprezentacje); $i++) {
                                    $kraj = pg_fetch_array($reprezentacje)['kraj'];
                                    echo "<option value='" . $kraj . "'>" . $kraj . "</option>";
                                }
                            ?>
                        </select>
                    </li>
                    <input type="hidden" name="idKonkursu" value="<?php echo $_GET['idKonkursu'] ?>">
                    <li class="flex-right submit">
                        <input type="submit" value="Zgłoś się"> 
                    </li>
                </ul>
                <?php
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        if ($_POST['kraj'] == '---') {
                            echo "<p class='red'>Nie wybrano kraju</p>";
                            return;
                        }

                        $reprezentacja = pg_fetch_array(pg_query_params($database, "SELECT * FROM reprezentacja WHERE idkonkursu=$1 AND kraj=$2" , array($_POST['idKonkursu'], $_POST['kraj'])));

                        $zgloszenia = pg_query_params($database, "SELECT * FROM zgloszenie WHERE idreprezentacji=$1" , array($reprezentacja['idreprezentacji']));

                        if ($reprezentacja['kwotastartowa'] - pg_num_rows($zgloszenia) <= 0) {
                            echo "<p class='red'>Brak wolnych miejsc w reprezentacji</p>";
                        } else {
                            $zgloszenie = pg_query_params($database, "INSERT INTO zgloszenie (idreprezentacji, iduczestnika) VALUES ($1, $2)", array($reprezentacja['idreprezentacji'], $_SESSION['iduczestnika']));
                            if ($zgloszenie == false) {
                                die("Database error");
                            }
                            header("Location: /~ak438500/skokiBD/zawodnik");
                        }
                    }
                ?>
            </form>
        </div>
    </div>
</div>

<?php include('../template/bottom.php'); ?>