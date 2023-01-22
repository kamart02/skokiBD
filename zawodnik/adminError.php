<!DOCTYPE html>

<?php
    session_start();
    if (isset($_SESSION['admin']) === false) {
        header("Location: /~ak438500/skokiBD/info");
    }
?>

<head>
    <?php include('../template/head.php'); ?>
</head>

<?php include('../template/top.php'); ?>

    <div class="content red flex-center">
        <h1>Tylko zawodnicy mogą kożystać z tego panelu</h1>
    </div>


<?php include('../template/bottom.php'); ?>