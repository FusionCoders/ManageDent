<?php
include_once("../html/sidebar.php");
include_once("../php/db_functions.php");
?>
<!Doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
    <link rel="stylesheet" href="../css/style_menu.css">
    <link rel="stylesheet" href="../css/appointmentsAdd.css">
</head>

<body class="main-body">
    <?php
        echo '<style>.sidebar .nav_menu .appointments { color: ' . 'white' . '; }</style>';
    ?>
    <section class="main-section">
        <h1>Appointments</h1>
        <section id="content">    
            <h2>Add Appointment</h2>

            <form action="../php/appointmentsAdd_functions.php" method="POST">

                <div class="input-box">
                    <label>Date:</label>
                    <input id="date_appointment" type="date" name="date_appointment" value="" required>
                </div>

                <div class="input-box">
                    <label>Dentist:</label>
                    <select name="dentist_id">
                    <?php 
                    $all_dentists = getAllDentists();
                    foreach ($all_dentists as $dentist) {
                        if($dentist['active_dentist'] == 1) {
                            echo '<option value="' . $dentist['id'] . '">' . $dentist['person_name'] . '</option>';
                        }
                    }
                    ?>
                    </select>
                    <p class="warning">
                    <?php 
                        if (isset($_GET['message'])) {
                            $message = urldecode($_GET['message']);
                            if ($message == 'dentist unavailable') {
                                echo 'The dentist already has another appointment at that time.';
                            } elseif ($message == 'dentist not working') {
                                echo 'The dentist is not working at that time.';
                            }                            
                        }
                    ?></p>
                </div>

                <div class="input-box">
                    <label>Patient:</label>
                    <select name="patient_id">
                    <?php 
                    $all_patients = getAllPatients();
                    foreach ($all_patients as $patient) {
                        echo '<option value="' . $patient['id'] . '">' . $patient['person_name'] . '</option>';   
                    }
                    ?>
                    </select>
                    <p class="warning">
                    <?php 
                        if (isset($_GET['message'])) {
                            $message = urldecode($_GET['message']);
                            if ($message == 'patient unavailable') {
                                echo 'The patient already has another appointment at that time.';
                            } 
                        }
                    ?></p>
                </div>

                <div class="input-box">
                    <label>Assistant:</label>
                    <select name="assistant_id">
                    <?php 
                    $all_assistants = getAllAssistants();
                    foreach ($all_assistants as $assistant) {
                        if($assistant['active_assistant'] == 1) {
                            echo '<option value="' . $assistant['id'] . '">' . $assistant['person_name'] . '</option>'; 
                        }  
                    }
                    ?>
                    </select>
                    <p class="warning">
                    <?php 
                        if (isset($_GET['message'])) {
                            $message = urldecode($_GET['message']);
                            if ($message == 'assistant unavailable') {
                                echo 'The assistant already has another appointment at that time.';
                            } 
                        }
                    ?></p>
                </div>

                <div class="input-box">
                    <label>Schedule:</label>
                    <div class="hours" id="hours">
                        <input type="time" name="start_time" value="' . $start_time . '"required>
                        <p> - </p>
                        <input type="time" name="end_time" value="' . $end_time . '"required>
                    </div>
                    <p class="warning">
                    <?php 
                        if (isset($_GET['message'])) {
                            $message = urldecode($_GET['message']);
                            if ($message == 'incorrect schedule') {
                                echo 'This schedule is not allowed.';
                            } 
                        }
                    ?></p>
                </div>

                <div class="input-box" id="machines">
                    <label>Machines</label>
                    <div class="machines">
                        <?php 
                        $all_machines = getAllMachines();
                        foreach ($all_machines as $machine) {
                            if ($machine['active_machine']==1) {
                                $reference_number = $machine['reference_number'];
                                echo '<div class="list_mac">';
                                echo '<input type="checkbox" class="list_item" name="machine'.$reference_number.'" value="' . $machine['reference_number']. '">';
                                echo '<p>'.$machine['machine_name'].' '.$machine['model'].', '.$machine['brand_name'].'</p>';
                                echo '</div>';
                                
                                echo '<p class="warning" id="warn_machine">';
                                if (isset($_GET['message'])) {
                                    $message = urldecode($_GET['message']);
                                    if ($message ==  $reference_number) {
                                    echo 'The machine'.$machine['machine_name'].' '.$machine['model'].', '.$machine['brand_name'] .' is unavailable at this time.';
                                    }
                                }
                                echo '</p>';
                            }
                        }
                        ?>
                    </div>
        
                </div>

                <button type="submit" class="btn" name="cancel" id="btn_cancel" formnovalidate>Return</button>
                <button type="submit" class="btn" name="add_appointment" id="btn_add">Add</button>
                </form>


        </section>
    </section>

</body>

</html>