<!DOCTYPE html>

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/default.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js"></script>
    <script>
        hljs.highlightAll();
    </script>
</head>

<body>
    <div class="nav">
        <div class="box left">
            <a class="brand" href="index.php">
                <div class="brand-text">SkokiBD</div>
            </a>
            <a class="element" href="index.php">Informacje</a>
            <a class="element" href="pKibica.html">Panel kibica</a>
            <a class="element" href="pAdministracyjny.html">Panel administracyjny</a>
        </div>
    </div>
    <div class="content">
        <h1>Informacje związane z rozwiązaniem zadania</h1>
        <h2>Wykres ERD</h2>
        <img src="SkokiERD.svg" alt="Zdjęcie wykresu ERD">
        <h2>Model logiczny</h2>
        <pre><code><?php include('./sql/dbCreate.sql'); ?></code></pre>
    </div>
</body>