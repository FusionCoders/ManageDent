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
    <title>Patient</title>
    <link rel="stylesheet" href="../css/style_menu.css">
    <link rel="stylesheet" href="../css/patients.css">
    <script>
    function navigateToPage(url) {
      window.location.href = url;
    }
  </script>
</head>

<body class="main-body">
    <?php
        echo '<style>.sidebar .nav_menu .patients { color: ' . 'white' . '; }</style>';
    ?>
    <section class="main-section">
        <div class="header-section">

            <h1>Patients</h1>
            <?php
            $message = 'add';
            echo '<div class="add-Pat">
                <a href="patientsEdit.php?message=' . urlencode($message) . '">
                    <button class="button" id="add" >Add</button>
                </a>
            </div>';
            ?>
            

        </div>
        <?php  

            
            $all_patients = getAllPatients();

            echo '<div class="table-container">';
            echo '<table>';
            echo '<tr>';
            echo '<th>ID</th>';
            echo '<th>Name</th>';
            echo '<th>Tax ID</th>';
            echo '<th>Date of Birth</th>';
            echo '<th>Phone Number</th>';
            echo '<th>Email</th>';

            echo '</tr>';
            
            foreach ($all_patients as $patient) {
                $id = $patient['id'];
                $person_name = $patient['person_name'];
                $tax_id = $patient['tax_id'];
                $birth_date = $patient['birth_date'];
                $phone_number = $patient['phone_number'];
                $email = $patient['email'];
                
                echo '<tr onclick="navigateToPage(\'patientsEdit.php?message=' .$id.'\')">';
                echo '<td>' . $id . '</td>';
                echo '<td>' . $person_name . '</td>';
                echo '<td>' . $tax_id . '</td>';
                echo '<td>' . $birth_date . '</td>';
                echo '<td>' . $phone_number . '</td>';
                echo '<td>' . $email . '</td>';
                echo '</tr>';
            }
            
            echo '</table>';
            echo '</div>';
            
        
        ?>
    </section>

</body>

</html>
