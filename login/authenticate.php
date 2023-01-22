<?php 
    session_start();
    $_SESSION['loggedIn'] = true;
    if ($_POST['username'] == 'admin') {
        $_SESSION['admin'] = true;
        $_SESSION['username'] = "AdminDebugUser";
    } else {
        $_SESSION['username'] = "DebugUser";
    }
    if (isset($_POST['next'])) {
        error_log($_POST['next']);
        header("Location: " . $_POST['next']);
    } else {
        header("Location: /~ak438500/skokiBD/info");
    }
?>