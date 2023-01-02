<!DOCTYPE html>

<?php
    session_start();
    if (isset($_SESSION['admin']) === true) {
        header("Location: /skokiBD/info");
    }
?>

<head>
    <?php include('../template/head.php'); ?>
</head>

<?php include('../template/top.php'); ?>

    <div class="content red flex-center">
        <h1>Nie jesteś administratorem systemu, zaloguj się na konto administracyjne aby skożystać z tego panelu.</h1>
    </div>


<?php include('../template/bottom.php'); ?>