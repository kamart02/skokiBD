<!DOCTYPE html>

<?php 
    session_start();

    if (isset($_SESSION['loggedIn']) === true) {
        header("Location: /~ak438500/skokiBD/login/noLoginError.php");
    }

?>

<?php include('../sql/dbLog.php'); ?>

<head>
    <?php include('../template/head.php') ?>
</head>

<?php include('../template/top.php'); ?>

    <div class="content flex-center">
        <div class="input-box">
            <h1>Rejestracja zawodnika</h1>
            <form method="POST">
                <ul class="input-list">
                    <li>
                        <label for="text">Imie</label>
                        <input type="text" name="imie" required min=1 max=255>
                    </li>
                    <li>
                        <label for="text">Nazwisko</label>
                        <input type="text" name="nazwisko" required min=1 max=255>
                    <li>
                        <label for="username">Login</label>
                        <input type="text" name="username" required min=1 max=64>
                    </li>
                    <li>
                        <label for="password">Hasło</label>
                        <input type="password" name="password" required min=1 max=255>
                    </li>
                    </li>
                    <li class="flex-right submit">
                        <input type="submit" value="Zajerestruj"> 
                    </li>
                </ul>
                <?php
                    if (isset($_GET['next']) === true) {
                        echo "<input type='hidden' name='next' value='" . $_GET['next'] . "'>";
                    }
                ?>
            </form>
            <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $database = pg_connect($DBLOGINSTR);
                    if ($database == false) {
                        die("Database error");
                    }
                    $querry = pg_query_params($database, "SELECT istniejeLogin($1) AS wartosc", array(htmlspecialchars($_POST['username'])));
                    if ($querry == false) {
                        die("Database error");
                    }
                    $result = pg_fetch_assoc($querry);
                    if ($result['wartosc'] == 't') {
                        echo "<p class='red'>Podany login jest już zajęty</p>";
                    } else {
                        $imie = htmlspecialchars($_POST['imie']);
                        $nazwisko = htmlspecialchars($_POST['nazwisko']);
                        $username = htmlspecialchars($_POST['username']);
                        $password = htmlspecialchars($_POST['password']);
                        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                        $querry = pg_query_params($database, "INSERT INTO uzytkownik(nazwa, hash_hasla) VALUES ($1, $2)", array($username, $passwordHash));
                        if ($querry == false) {
                            die("Database error");
                        }
                        $querry = pg_query_params($database, "INSERT INTO uczestnik(imie, nazwisko, nazwa) VALUES ($1, $2, $3)", array($imie, $nazwisko, $username));
                        if ($querry == false) {
                            die("Database error");
                        }
                        pg_close($database);
                        session_start();

                        if (isset($_POST['next']) === true) {
                            header('Location: /~ak438500/skokiBD/login/login.php?next='  . $_POST['next']);
                        } else {
                            header('Location: /~ak438500/skokiBD/login/login.php');
                        }
                    }
                }
            ?>
        </div>
    </div>

<?php include('../template/bottom.php'); ?>