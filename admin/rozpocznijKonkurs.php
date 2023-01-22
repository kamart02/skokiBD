<?php include('adminTest.php'); ?>
<?php include('../sql/dbLog.php'); ?>

<?php
    if (!isset($_GET['idkonkursu'])) {
        die("Nieprawidłowy request");
    }

    $database = pg_connect($DBLOGINSTR);
    if ($database == false) {
        die("Database error");
    }

   
    $konkurs = pg_query_params($database, "SELECT * FROM konkurs WHERE idkonkursu=$1", array($_GET['idkonkursu']));
    $konkursRow = pg_fetch_assoc($konkurs);

    if ($konkursRow['zamknietezgloszenia'] == 'f') {
        pg_close($database);
        header("Location: /~ak438500/skokiBD/admin/otwarteZgloszeniaError.php?next=/~ak438500/skokiBD/admin");
        return;
    }

    $querry = pg_query_params($database, "SELECT istniejeKonkurs($1) AS wartosc", array($_GET['idkonkursu']));
    $row = pg_fetch_row($querry);
    if (!$row[0]) {
        die ("Nieprawidłowe dane GET");
    }

    $querry = pg_query_params($database, "SELECT * FROM konkurs WHERE idkonkursu=$1", array($_GET['idkonkursu']));
    if ((pg_field_is_null($querry, 0, "seriapierwsza") == 0 || pg_field_is_null($querry, 0, "seriakwalifikacyjna") == 0) && !isset($_GET['seria'])) {
        pg_close($database);
        header("Location: /~ak438500/skokiBD/admin/konkurs.php?idkonkursu=" . $_GET['idkonkursu']);
    }

    $zgloszenia = pg_query_params($database, "SELECT * FROM zgloszenie WHERE zgloszenie.idreprezentacji IN (SELECT idreprezentacji FROM reprezentacja WHERE idkonkursu=$1)", array($_GET['idkonkursu']));
    $liczbaZgloszen = pg_num_rows($zgloszenia);

    if (!isset($_GET['seria'])) {
        if ($liczbaZgloszen > 50) {
            $typSerii = 'kwalifikacyjna';
        } else {
            $typSerii = 'pierwsza';
        }
        $zgloszenia = pg_fetch_all($zgloszenia);
    } else {
        $typSerii = $_GET['seria'];
        
        if ($typSerii == 'pierwsza') {
            $idPoprzedniejSerii = pg_query_params($database, "SELECT seriakwalifikacyjna FROM konkurs WHERE idkonkursu=$1", array($_GET['idkonkursu']));
        } else {
            $idPoprzedniejSerii = pg_query_params($database, "SELECT seriapierwsza FROM konkurs WHERE idkonkursu=$1", array($_GET['idkonkursu']));
        }

        $idPoprzedniejSerii = pg_fetch_row($idPoprzedniejSerii);
        $idPoprzedniejSerii = $idPoprzedniejSerii[0];

        if ($typSerii == 'pierwsza') {
            $zgloszenia = pg_query_params($database, "SELECT * FROM skok A WHERE A.idserii=$1 AND A.ocena IN (SELECT distinct B.ocena FROM skok B WHERE B.idserii=$1 AND B.dyskwalifikacja='f' ORDER BY B.ocena desc LIMIT 50) ORDER BY A.ocena", array($idPoprzedniejSerii));
        } else {
            $zgloszenia = pg_query_params($database, "SELECT * FROM skok A WHERE A.idserii=$1 AND A.ocena IN (SELECT distinct B.ocena FROM skok B WHERE B.idserii=$1 ORDER BY B.ocena desc LIMIT 30) ORDER BY A.ocena", array($idPoprzedniejSerii));
        }
        $zgloszenia = pg_fetch_all($zgloszenia);
    }


    $idSerii = pg_query_params($database, "INSERT INTO seria (typserii) VALUES ($1) RETURNING idserii", array($typSerii));
    if ($idSerii == false) {
        die("Database error");
    }
    $idSerii = pg_fetch_row($idSerii);
    $idSerii = $idSerii[0];
    if ($typSerii == 'kwalifikacyjna') {
        pg_query_params($database, "UPDATE konkurs SET seriakwalifikacyjna=$1 WHERE idkonkursu=$2", array($idSerii, $_GET['idkonkursu']));
    } else if ($typSerii == 'pierwsza') {
        pg_query_params($database, "UPDATE konkurs SET seriapierwsza=$1 WHERE idkonkursu=$2", array($idSerii, $_GET['idkonkursu']));
    } else {
        pg_query_params($database, "UPDATE konkurs SET seriadruga=$1 WHERE idkonkursu=$2", array($idSerii, $_GET['idkonkursu']));
    }


    
    $idZgloszen = array();
    for ($i = 0; $i < $liczbaZgloszen; $i++) {
        $idZgloszen[$i] = $zgloszenia[$i]['idzgloszenia'];
    }
    if (!isset($_GET['seria'])) {
        shuffle($idZgloszen);
    }

    for ($i = 0; $i < $liczbaZgloszen; $i++) {
        pg_query_params($database, "INSERT INTO skok (idzgloszenia, idserii, numerstartowy) VALUES ($1, $2, $3)", array($idZgloszen[$i], $idSerii, $i + 1));
    }

    pg_close($database);

    header("Location: /~ak438500/skokiBD/admin/konkurs.php?idkonkursu=" . $_GET['idkonkursu'] . "&seria=" . $typSerii);
?>