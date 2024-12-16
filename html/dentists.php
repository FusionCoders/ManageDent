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
    <title>Dentists</title>
    <link rel="stylesheet" href="../css/style_menu.css">
    <link rel="stylesheet" href="../css/dentists.css">
</head>

<body class="main-body">
    <?php
        echo '<style>.sidebar .nav_menu .dentists { color: ' . 'white' . '; }</style>';
    ?>
    <section class="main-section">
        <h1>Dentists</h1>
        <h2>Active Dentists</h2>
        <section class= "active">
            <?php
                $result = getPhotosDentists();
                for ($i = 0; $i < count($result); $i++) {
                    if($result[$i]['active_dentist']==1){
                        $message = $result[$i]['id'];
                        echo '<div class="single-Den">
                                <img src="' . $result[$i]['photo'] . '" alt="Den">
                                <a href="dentistsEdit.php?message=' . urlencode($message) . '">
                                    <div class="overlay"></div>
                                    <div class="denName">
                                        <h3>'. $result[$i]['person_name'] .'</h3>
                                        <h4>'. $result[$i]['email'] .'</h4>
                                    </div>
                                </a>
                            </div>';
                    }
                }
                $message = 'add';
                echo '<div class="single-Den">
                        <img src="' . '../img/Add.png' . '" alt="Den">
                        <a href="dentistsEdit.php?message=' . urlencode($message) . '">
                            <div class="overlay"></div>
                            <div class="denName">
                                <h3>Add Dentist</h3>
                            </div>
                        </a>
                    </div>';
            ?>
        </section>
        <h2>Inactive Dentists</h2>
        <section class="inactive">
            <?php
                for ($i = 0; $i < count($result); $i++) {
                    if($result[$i]['active_dentist']==0){
                        $message = $result[$i]['id'];
                        echo '<div class="single-Den">
                                <img src="' . $result[$i]['photo'] . '" alt="Den">
                                <a href="dentistsEdit.php?message=' . urlencode($message) . '">
                                    <div class="overlay"></div>
                                    <div class="denName">
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