<?php
    session_start();
    if (isset($_SESSION['loggedIn']) === true) {
        if (isset($_SESSION['admin']) === false) {
            header('Location: /skokiBD/admin/noAdminError.php');
        }
    } else {
        header('Location: /skokiBD/login/login.php?next=' . '/skokiBD/admin');
    }
?>  