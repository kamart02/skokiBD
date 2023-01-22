<!DOCTYPE html>

<?php 
    session_start();
    if (isset($_SESSION['loggedIn']) === true) {
        header("Location: /~ak438500/skokiBD/login/noLoginError.php");
    }
?>

<head>
    <?php include('../template/head.php') ?>
</head>

<?php include('../template/top.php'); ?>

    <div class="content flex-center">
        <div class="input-box">
            <h1>Logowanie użytkownika</h1>
            <form method="POST">
                <ul class="input-list">
                    <li>
                        <label for="username">Login</label>
                        <input type="text" name="username">
                    </li>
                    <li>
                        <label for="password">Hasło</label>
                        <input type="password" name="password">
                    </li>
                    <li class="flex-right submit">
                        <input type="submit" value="Zaloguj"> 
                    </li>
                </ul>
                <?php
                    if (isset($_GET['next']) === true) {
                        echo "<input type='hidden' name='next' value='" . $_GET['next'] . "'>";
                    }
                ?>
            </form>
            <?php 
                include('../sql/dbLog.php');
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $database = pg_connect($DBLOGINSTR);
                    if ($database == false) {
                        die("Database error");
                    }
                    $querry = pg_query_params($database, "SELECT * FROM uzytkownik WHERE nazwa = $1", array($_POST['username']));
                    if ($querry == false) {
                        die("Database error");
                    }
                    password_hash("123", PASSWORD_DEFAULT);

                    if (pg_num_rows($querry) == 0) {
                        echo "<p class='red'>Niepoprawny login lub hasło</p>";
                        return;
                    } else {

                        $row = pg_fetch_array($querry);
                        if (password_verify($_POST['password'], $row['hash_hasla']) === false) {
                            echo "<p class='red'>Niepoprawny login lub hasło</p>";
                            return;
                        }

                        $_SESSION['loggedIn'] = true;
                        $_SESSION['username'] = $row['nazwa'];

                        if ($row['administrator'] == "t") {
                            $_SESSION['admin'] = true;
                        } else {
                            $uczestnikQuerry = pg_query_params($database, "SELECT iduczestnika FROM uczestnik WHERE nazwa=$1", array($_SESSION['username']));
                            $_SESSION['iduczestnika'] = pg_fetch_array($uczestnikQuerry)['iduczestnika'];
                        }

                        if (isset($_POST['next'])) {
                            header("Location: " . $_POST['next']);
                        } else {
                            header("Location: /~ak438500/skokiBD/info");
                        }
                    }
                }
            ?>
        </div>
    </div>

<?php include('../template/bottom.php'); ?>