<?php 
include_once("../php/db_functions.php");
include_once("../html/sidebar.php");
?>
<!Doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assistants</title>
    <link rel="stylesheet" href="../css/style_menu.css">
    <link rel="stylesheet" href="../css/assistants.css">
</head>

<body class="main-body">
    <?php
        echo '<style>.sidebar .nav_menu .assistants { color: ' . 'white' . '; }</style>';
    ?>
    <section class="main-section">
        <h1>Assistants</h1>
        <h2>Active Assistants</h2>
        <section class= "active">
            <?php
                $result = getPhotosAssistants();
                for ($i = 0; $i < count($result); $i++) {
                    if($result[$i]['active_assistant']==1){
                        $message = $result[$i]['id'];
                        echo '<div class="single-Ass">
                                <img src="' . $result[$i]['photo'] . '" alt="Ass">
                                <a href="assistantsEdit.php?message=' . urlencode($message) . '">
                                    <div class="overlay"></div>
                                    <div class="assName">
                                        <h3>'. $result[$i]['person_name'] .'</h3>
                                        <h4>'. $result[$i]['email'] .'</h4>
                                    </div>
                                </a>
                            </div>';
                    }
                }
                $message = 'add';
                echo '<div class="single-Ass">
                        <img src="' . '../img/Add.png' . '" alt="Ass">
                        <a href="assistantsEdit.php?message=' . urlencode($message) . '">
                            <div class="overlay"></div>
                            <div class="assName">
                                <h3>Add Assistant</h3>
                            </div>
                        </a>
                    </div>';
            ?>
        </section>
        <h2>Inactive Assistants</h2>
        <section class="inactive">
            <?php
                for ($i = 0; $i < count($result); $i++) {
                    if($result[$i]['active_assistant']==0){
                        $message = $result[$i]['id'];
                        echo '<div class="single-Ass">
                                <img src="' . $result[$i]['photo'] . '" alt="Ass">
                                <a href="assistantsEdit.php?message=' . urlencode($message) . '">
                                    <div class="overlay"></div>
                                    <div class="assName">
                                        <h3>'. $result[$i]['person_name'] .'</h3>
                                        <h4>'. $result[$i]['email'] .'</h4>
                                    </div>
                                </a>
                            </div>';
                    }
                }
            ?>
        </section>

    </section>

</body>

</html>