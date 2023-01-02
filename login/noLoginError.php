<!DOCTYPE html>

<?php
    session_start();
    if ($_SESSION['loggedIn'] === true) {
        header("Location: /skokiBD/info");
    }
?>

<head>
    <?php include('../template/head.php'); ?>
</head>

<?php include('../template/top.php'); ?>

    <div class="content red flex-center">
        <h1>Jesteś już zalogowany, przed zmianą użytkownika należy się wylogować.</h1>
    </div>


<?php include('../template/bottom.php'); ?>