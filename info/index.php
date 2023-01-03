<!DOCTYPE html>

<head>
    <?php include('../template/head.php'); ?>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/default.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js"></script>
    <script>
        hljs.highlightAll();
    </script>
</head>

<?php include('../template/top.php'); ?>

<div class="content">
    <h1>Informacje związane z rozwiązaniem zadania</h1>
    <pre>
        Częściowo działający panel administracyjny.
        Można dodawać zawody ale brakuje jeszcze kwot startowych.
        Konto administracjyje ma login admin. Hasło dowolne.
    </pre>
    <h2>Wykres ERD</h2>
    <img id="ERDModel" src="/~ak438500/skokiBD/info/SkokiERD.svg" alt="Zdjęcie wykresu ERD">
    <h2>Model logiczny</h2>
    <pre><code><?php include('../sql/dbCreate.sql'); ?></code></pre>
</div>

<?php include('../template/bottom.php'); ?>