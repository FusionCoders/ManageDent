<?php 
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
    <link rel="stylesheet" href="../css/appointmentsMenu.css">
</head>

<body class="main-body">
    <?php
        echo '<style>.sidebar .nav_menu .appointments{ color: ' . 'white' . '; }</style>';
    ?>

    <section class="main-section">

        <?php
            $message = 'appointments';
            echo '<div class="item">
                    <a href="appointments.php">
                    <h3>Appointments</h3>
                    <div class="overlay"></div>   
                    <div class="itemName">
                        <h4>Go to appointments</h4>
                    </div>
                    </a>
                </div>';
            $message = 'reports';
            echo '<div class="item">
                    <a href="reports.php">
                    <h3>Reports</h3>
                    <div class="overlay"></div>
                    <div class="itemName">
                        <h4>Go to reports</h4>
                    </div>
                    </a>
                </div>';
        ?>

    </section>

</body>

</html>