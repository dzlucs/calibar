<?php
define('DB_PATH', '../database/chat.txt');

$messages = file(DB_PATH, FILE_IGNORE_NEW_LINES);
# ignoradas as quebras de linhas na inclusão de arrays
?>

<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/assets/css/aplication.css">
    <title>Super chat</title>


</head>

<body>
    <header>
        <h1>Super chat</h1>
    </header>

    <section class="messages">

        <?php foreach($messages as $index => $message): ?>

            <div class="message <?= ['sent', 'received'][$index % 2]?>">
                <?= $message ?>
            </div>

        <?php endforeach ?>

    </section>

    <footer>
        <form action="/" method="POST">
            <input id="message" type="text" placeholder="Type a message" name="message">
            <input type="submit" value="Enviar">
        </form>
    </footer>
</body>
</html>