<!DOCTYPE html>

<?php include('adminTest.php'); ?>

<head>
    <?php include('../template/head.php'); ?>
</head>

<?php include('../template/top.php'); ?>

<div class="flex-center content">
    <div class="input-box">
        <h1>Nowy konkurs</h1>
        <form method="post">
            <ul class="input-list">
                <li>
                    <label for="nazwa">Nazwa konkursu</label>
                    <input type="text" name="nazwa" required autofocus maxlength="64">
                </li>
                <li>
                    <label for="lokalizacja">Lokalizacja</label>
                    <input type="text" name="lokalizacja" required maxlength="64">
                </li>
                <li>
                    <label for="data">Data konkursu</label>
                    <input type="date" name="data" required>
                </li>
                <li class="flex-right submit">
                    <input type="submit" value="Dodaj">
                </li>
            </ul>
        </form>
        <?php
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                $validationPassed = true;
                if (!is_string($_POST['nazwa'])) {
                    echo "<p class='red'>Nazwa konkursu musi być napisem</p>";
                    $validationPassed = false;
                } else {
                    $nazwa = trim(htmlspecialchars($_POST['nazwa']));
                    if (strlen($nazwa) <= 0) {
                        echo "<p class='red'>Nazwa konkursu nie może być pusta</p>";
                        $validationPassed = false;
                    } else if (strlen($nazwa) > 64) {
                        echo "<p class='red'>Nazwa konkursu nie może być dłuższa niż 64 znaki</p>";
                        $validationPassed = false;
                    }
                }

                if (!is_string($_POST['lokalizacja'])) {
                    echo "<p class='red'>Lokalizacja konkursu musi być napisem</p>";
                    $validationPassed = false;
                } else {
                    $nazwa = trim(htmlspecialchars($_POST['lokalizacja']));
                    if (strlen($nazwa) <= 0) {
                        echo "<p class='red'>Lokalizacja konkursu nie może być pusta</p>";
                        $validationPassed = false;
                    } else if (strlen($nazwa) > 64) {
                        echo "<p class='red'>Lokalizacja konkursu nie może być dłuższa niż 64 znaki</p>";
                        $validationPassed = false;
                    }
                }

                $data = strtotime($_POST['data']);
                if ($data == false) {
                    echo "<p class='red'>Nieprawidłowa data</p>";
                    $validationPassed = false;
                }

                if ($validationPassed == true) {
                    
                }
            }
        ?>
    </div>
</div>

<?php include('../template/bottom.php'); ?>