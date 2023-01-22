<?php
    session_start();
    if (isset($_SESSION['loggedIn']) === true) {
        if (isset($_SESSION['admin']) === false) {
            header('Location: /~ak438500/skokiBD/admin/noAdminError.php');
        }
    } else {
        header('Location: /~ak438500/skokiBD/login/login.php?next=' . '/~ak438500/skokiBD/admin');
    }
?>  