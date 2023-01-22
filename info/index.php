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
        System podzielony jest na 3 typy użytkowników:
        - Zalogowani:
            - administrator
            - zawodnik
        - Nie zalogowani:
            - kibic

        Każdy z użytkowników może kożystać z panelu kibica.
        Każdy użytkownik niezalogowany może zarejestrować się jako zawodnik.

        Zawodnicy:
        - Mogą przeglądać konkursy w systemie i się na nie zapisywać w panelu zawodnika.
        - Podczas zapisu na konkurs zawodnicy wypierają swoje reprezentacje.

        Administrator:
        - Może tworzyć konkursy
        - Zamykac i otwierać zapisy na konkurs
        - Edytować kwoty startowe
        - Rozpoczynać i edytować wyniki zawodów

        Przebieg konkursu:
        Administrator po stworzeniu konkursu wypełnia kwoty startowe. Wtedy może otworzyć zapisy.
        Każdy z uczestników indywidualnie zapisuje się na konkurs.
        Po zakończeniu zapisów administrator zamyka zapisy i rozpoczyna konkurs.
        Wpisuje wyniki zawodników w odpowiedniej serii.
        System sortuje zawodników w serii albo losowo (w przypadku braku wcześniejszej serii) lub po ocenie skoku.
        Administrator po wpisaniu wyników do serii może rozpocząć następną przyciskiem następna seria.
        Po wpisaniu wyników do ostatniej serii administrator może zakończyć konkurs.

        W systemie istnieją przykładowe konta:
        - admin - administrator systemu
        - kamilStoch - zawodnik
        - piotrŻyła - zawodnik
        - markusEisenbichler - zawodnik
        - karlGeiger - zawodnik

        Oraz istnieją dwa przykładowe konkursy, jeden zakończony, drugi jeszcze nie rozpoczęty.

        Kod źródłowy projektu dostępny pod adresem: https://github.com/kamart02/skokiBD
    </pre>
    <a href="https://github.com/kamart02/skokiBD">Kod źródłowy</a>
    <h2>Wykres ERD</h2>
    <img id="ERDModel" src="/~ak438500/skokiBD/info/SkokiERD.svg" alt="Zdjęcie wykresu ERD">
    <h2>Model logiczny</h2>
    <pre><code><?php include('../sql/dbCreate.sql'); ?></code></pre>
</div>

<?php include('../template/bottom.php'); ?>