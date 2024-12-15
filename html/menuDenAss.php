<?php 
include_once("../html/sidebar.php");
?>
<!Doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Menu</title>
    <link rel="stylesheet" href="../css/style_menu.css">
    <link rel="stylesheet" href="../css/menuAdm.css">
</head>
<body class="main-body">
    <section class='main-section'>
        <?php
            //session_start();
            $role = $_SESSION['role'];

            echo '<h1>WELCOME TO THE SPACE</h1>';

            if ($role == 'dentist') {
                echo '<h1>DENTIST</h1>';
            } elseif ($role == 'assistant') {
                echo '<h1>ASSISTANT</h1>';
            }
        ?>
    </section>
</body>

</html>