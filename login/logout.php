<?php
    session_start();
    // $_SESSION['loggedIn'] = false;
    // session_abort();
    session_destroy();
    header("Location: /skokiBD/info");
?>