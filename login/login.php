<!DOCTYPE html>

<?php 
    session_start();
    if ($_SESSION['loggedIn'] === true) {
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
            <form method="POST" action="authenticate.php">
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
        </div>
    </div>

<?php include('../template/bottom.php'); ?>