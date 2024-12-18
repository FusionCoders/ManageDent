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
    <title>Machines</title>
    <link rel="stylesheet" href="../css/style_menu.css">
    <link rel="stylesheet" href="../css/machines.css">
</head>

<body class="main-body">
    <?php
        echo '<style>.sidebar .nav_menu .machines { color: ' . 'white' . '; }</style>';
    ?>
    <section class="main-section">
        <h1>Machines</h1>
        <h2>Active Machines</h2>
        <section class= "active">
            <?php
                $result = getPhotosMachines();
                for ($i = 0; $i < count($result); $i++) {
                    if($result[$i]['active_machine']==1){
                        $message = $result[$i]['reference_number'];
                        echo '<div class="single-Mac">
                                <img src="' . $result[$i]['photo'] . '" alt="Mac">
                                <a href="machinesEdit.php?message=' . urlencode($message) . '">
                                    <div class="overlay"></div>   
                                    <div class="macName">
                                        <h3>'. $result[$i]['machine_name'] .'</h3>
                                        <h4>'. $result[$i]['model'] .'</h4>
                                    </div>
                                </a>
                            </div>';
                    }
                }
                $message = 'add';
                echo '<div class="single-Mac">
                        <img src="' . '../img/Add.png' . '" alt="Mac">
                        <a href="machinesEdit.php?message=' . urlencode($message) . '">
                            <div class="overlay"></div>
                            <div class="macName">
                                <h3>Add Machine</h3>
                            </div>
                        </a>
                    </div>';
            ?>
        </section>
        <h2>Inactive Machines</h2>
        <section class="inactive">
            <?php
                for ($i = 0; $i < count($result); $i++) {
                    if($result[$i]['active_machine']==0){
                        $message = $result[$i]['reference_number'];
                        echo '<div class="single-Mac">
                                <img src="' . $result[$i]['photo'] . '" alt="Mac">
                                <a href="machinesEdit.php?message=' . urlencode($message) . '">
                                    <div class="overlay"></div>   
                                    <div class="macName">
                                        <h3>'. $result[$i]['machine_name'] .'</h3>
                                        <h4>'. $result[$i]['model'] .'</h4>
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