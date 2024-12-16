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
    <title>Reports</title>
    <link rel="stylesheet" href="../css/style_menu.css">
    <link rel="stylesheet" href="../css/reports.css">
</head>

<body class="main-body">
    <?php
        echo '<style>.sidebar .nav_menu .reports { color: ' . 'white' . '; }</style>';
    ?>
    <section class="main-section">
        <h1>Appointments</h1>
        <section id="content">    
            <h2>Reports</h2>
            <h3 class="title-date">Date:</h3>
            <form class="date-box" action="../php/reports_functions.php" method="POST">
            <?php 
                if (isset($_GET['date_appointment'])) {
                    $date_appointment = urldecode($_GET['date_appointment']);
                    echo '<input id="date_appointment" type="date" name="date_appointment" value="' . $date_appointment . '" required>';
                }else {
                    echo '<input id="date_appointment" type="date" name="date_appointment" value="' . date('Y-m-d') . '" required>';
                }
            ?>
            <button type="submit" class="btn" name="show_reports">Select</button>
            <button type="submit" class="btn" id="btn_return" name="return">Return</button>
            </form>

            <?php 
                if (isset($_GET['date_appointment'])) {
                    $date_appointment = urldecode($_GET['date_appointment']);
                }else{
                    $date_appointment = date('Y-m-d');
                }
                $reports = getReports($date_appointment);
                echo '<h4>My reports:</h4>';
                if (empty($reports)) {
                    echo '<p>There are no reports to present.</p>';
                } else {
                    foreach ($reports as $report) {
                        $name_patient = getPatientName($report['patient_id']);
                        $name_assistant = getAssistantName($report['assistant_id']);
                        $name_procedure = getProcedureName($report['medical_procedure_id']);
                        $id_appointment = $report['appointment_id'];
                        $hours_appointment = getHoursAppointment($id_appointment);
                        $start_time = $hours_appointment['start_time'];
                        $end_time = $hours_appointment['end_time'];
                        $name_machines = getMachinesName($id_appointment);

                        echo '<form class="report-box" action="../php/reports_functions.php?report_id='.$report['report_id'].'&date_appointment='. $date_appointment . '" method="POST">';

                        echo '<div class="input-box">';
                        echo '<label>Hour:</label>';
                        echo '<input type="type" name="start_time" value="' . $start_time . ' - ' . $end_time . '"readonly>';
                        echo '</div>';

                        echo '<div class="input-box">';
                        echo '<label>Patient:</label>';
                        echo '<input type="type" name="patient_id" value="' .  $name_patient . '"readonly>';
                        echo '</div>';

                        echo '<div class="input-box">';
                        echo '<label>Assistant:</label>';
                        echo '<input type="type" name="assistant_id" value="' . $name_assistant . '"readonly>';
                        echo '</div>';

                        echo '<div class="input-box">';
                        echo '<label>Procedure:</label>';
                        echo '<input type="type" name="procedure" value="' . $name_procedure . '"readonly>';
                        echo '</div>';

                        echo '<div class="input-box">';
                        echo '<label>Machines:</label>';
                        echo '<ul class="machines">';
                        if (empty($name_machines)) {
                            echo '<li>';
                            echo '<input class="list_item" type="type" name="machine" value="No machines were requested in this appointment."readonly>';
                            echo '</li>';
                        }else{
                            foreach ($name_machines as $name_machine) {;
                                echo '<li>';
                                echo '<input class="list_item" type="type" name="machine" value="' . $name_machine['machine_name'] . ', '. $name_machine['model'] .'"readonly>';
                                echo '</li>';
                            }
                        }
                        echo '</ul>';
                        echo '</div>';

                        echo '<div class="input-box">';
                        echo '<label>Observations:</label>';
                        echo '<span class="icon"><ion-icon name="create-outline"></ion-icon></span>';
                        echo '<textarea name="observations">' . $report['observations']. '</textarea>';
                        echo '</div>';

                        echo '<button type="submit" class="btn" name="delete" id="btn_delete">Delete</button>';
                        echo '<button type="submit" class="btn" name="edit" id="btn_save">Save Changes</button>';
                        
        
                        echo '</form>';
                    }
                }

                $all_reports = getAllReports($date_appointment);
                echo '<h4>Other reports:</h4>';
                if (empty($all_reports)) {
                    echo '<p>There are no reports to present.</p>';
                } else {
                    foreach ($all_reports as $report) {
                        $name_patient = getPatientName($report['patient_id']);
                        $name_assistant = getAssistantName($report['assistant_id']);
                        $name_dentist = getDentistName($report['dentist_id']);
                        $name_procedure = getProcedureName($report['medical_procedure_id']);
                        $id_appointment = $report['appointment_id'];
                        $hours_appointment = getHoursAppointment($id_appointment);
                        $start_time = $hours_appointment['start_time'];
                        $end_time = $hours_appointment['end_time'];
                        $name_machines = getMachinesName($id_appointment);


                        echo '<form class="report-box" id="read" action="../php/reports_functions.php?report_id='.$report['report_id'].'&date_appointment='. $date_appointment.'" method="POST">';

                        echo '<div class="input-box">';
                        echo '<label>Dentist:</label>';
                        echo '<input type="type" name="dentist" value="' . $name_dentist . '"readonly>';
                        echo '</div>';

                        echo '<div class="input-box">';
                        echo '<label>Hour:</label>';
                        echo '<input type="type" name="hours" value="' . $start_time . ' - ' . $end_time . '"readonly>';
                        echo '</div>';

                        echo '<div class="input-box">';
                        echo '<label>Patient:</label>';
                        echo '<input type="type" name="patient" value="' .  $name_patient . '"readonly>';
                        echo '</div>';

                        echo '<div class="input-box">';
                        echo '<label>Assistant:</label>';
                        echo '<input type="type" name="assistant" value="' . $name_assistant . '"readonly>';
                        echo '</div>';

                        echo '<div class="input-box">';
                        echo '<label>Procedure:</label>';
                        echo '<input type="type" name="procedure" value="' . $name_procedure . '"readonly>';
                        echo '</div>';

                        echo '<div class="input-box">';
                        echo '<label>Machines:</label>';
                        echo '<ul class="machines">';
                        if (empty($name_machines)) {
                            echo '<li>';
                            echo '<input class="list_item" type="type" name="machine" value="No machines were requested in this appointment."readonly>';
                            echo '</li>';
                        }else{
                            foreach ($name_machines as $name_machine) {;
                                echo '<li>';
                                echo '<input class="list_item" type="type" name="machine" value="' . $name_machine['machine_name'] . ', '. $name_machine['model'] .'"readonly>';
                                echo '</li>';
                            }
                        }
                        echo '</ul>';
                        echo '</div>';

                        echo '<div class="input-box">';
                        echo '<label>Observations:</label>';
                        echo '<textarea name="observations" readonly>' . $report['observations']. '</textarea>';
                        echo '</div>';

                        echo '</form>';
                    }
                }
                
            ?>

        </section>
    </section>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>