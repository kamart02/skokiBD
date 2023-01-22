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
        <h1>Nie można wykonać tej akcji gdy zgłoszenia są otwarte</h1>
    </div>
    <div class="flex-center">
        <a href="<?php echo $_GET['next']; ?>"><button>Wróć</button></a>
    </div>


<?php include('../template/bottom.php'); ?>