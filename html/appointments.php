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
    <link rel="stylesheet" href="../css/appointments.css">
</head>

<body class="main-body">
    <?php
        echo '<style>.sidebar .nav_menu .appointments { color: ' . 'white' . '; }</style>';
    ?>
    <section class="main-section">
        <h1>Appointments</h1>
        <section id="content">    
            <h2>Bookings</h2>
            <h3 class="title-date">Date:</h3>
            <form class="date-box" action="../php/appointments_functions.php" method="POST">
            <?php 
                if (isset($_GET['date_appointment'])) {
                    $date_appointment = urldecode($_GET['date_appointment']);
                    echo '<input id="date_appointment" type="date" name="date_appointment" value="' . $date_appointment . '" required>';
                }else {
                    echo '<input id="date_appointment" type="date" name="date_appointment" value="' . date('Y-m-d') . '" required>';
                }
            ?>
            <button type="submit" class="btn" name="show_appointments">Select</button>
            <button type="submit" class="btn" id="add" name="add">Add Appointment</button>
            <button type="submit" class="btn" id="btn_return" name="return">Return</button>
            </form>

            <?php 
                if (isset($_GET['date_appointment'])) {
                    $date_appointment = urldecode($_GET['date_appointment']);
                }else{
                    $date_appointment = date('Y-m-d');
                }
                $role = $_SESSION['role'];
                $appointments = getAppointments($date_appointment, $role);
                echo '<h4>My appointments:</h4>';
                if (empty($appointments)) {
                    echo '<p>There are no appointments scheduled.</p>';
                } else {
                    foreach ($appointments as $appointment) {
                        $patient_name = getPatientName($appointment['patient_id']);
                        if ($role == "dentist"){
                            $professional_name = getAssistantName($appointment['assistant_id']);
                        } elseif ($role == "assistant"){
                            $professional_name = getDentistName($appointment['dentist_id']);
                        }
                        $id_appointment = $appointment['appointment_id'];
                        $hours_appointment = getHoursAppointment($id_appointment);
                        $start_time = $hours_appointment['start_time'];
                        $end_time = $hours_appointment['end_time'];

                        //if (($date_appointment==date('Y-m-d') && $start_time>=date('H:i:s')) || $date_appointment>date('Y-m-d')) {
                            echo '<form class="appointment-box" action="../php/appointments_functions.php?appointment_id='.$id_appointment.'&date_appointment='. $date_appointment . '" method="POST">';
                        //}else{
                          //  echo '<form class="appointment-box" id="read" action="../php/appointments_functions.php?appointment_id='.$id_appointment.'&date_appointment='. $date_appointment . '" method="POST">';
                        //}
                    
                        echo '<div class="input-box">';
                        echo '<label>Hour:</label>';
                        echo '<div class="hours">';
                        if (($date_appointment==date('Y-m-d') && $start_time>=date('H:i:s')) || $date_appointment>date('Y-m-d')) {
                            echo '<span class="icon"><ion-icon name="create-outline"></ion-icon></span>';
                            echo '<input type="time" name="start_time" value="' . $start_time . '"required>';
                            echo '<p> - </p>';
                            echo '<input type="time" name="end_time" value="' . $end_time . '"required>';
                            
                        } else {
                            echo '<input type="time" name="start_time" value="' . $start_time . '"readonly>';
                            echo '<p> - </p>';
                            echo '<input type="time" name="end_time" value="' . $end_time . '"readonly>';
                        }
                        echo '</div>';
                        echo '</div>';
                        echo '<p class="warning">';
                            if (isset($_GET['message'])) {
                                $message = urldecode($_GET['message']);
                                $appointment_id = urldecode($_GET['appointment_id']);
                                if ($message=='schedule incorrect' && $id_appointment==$appointment_id) {
                                    echo 'This schedule is not allowed.';
                                } 
                            }
                        echo '</p>';

                        echo '<div class="input-box">';
                        echo '<label>Patient:</label>';
                        echo '<select name="patient_id" readonly>';
                        echo '<option value="' . $appointment['patient_id'] . '">' . $patient_name . '</option>';
                        echo '</select>';
                        echo '</div>';

                        echo '<div class="input-box">';
                        if ($role == "dentist"){
                            echo '<label>Assistant:</label>';
                            if (($date_appointment==date('Y-m-d') && $start_time>=date('H:i:s')) || $date_appointment>date('Y-m-d')) {
                                echo '<span class="icon"><ion-icon name="create-outline"></ion-icon></span>';
                                echo '<select name="assistant_id">';
                                $all_assistants = getAllAssistants();
                                echo '<option value="' . $appointment['assistant_id'] . '">' . $professional_name . '</option>';
                                foreach ($all_assistants as $assistant) {
                                    if($assistant['active_assistant'] == 1 && $appointment['assistant_id'] != $assistant['id']){
                                        echo '<option value="' . $assistant['id'] . '">' . $assistant['person_name'] . '</option>';
                                    }
                                }
                                echo '</select>';
                            } else {
                                echo '<select name="assistant_id" readonly>';
                                echo '<option value="' . $appointment['assistant_id'] . '">' . $professional_name . '</option>';
                                echo '</select>';
                            }
                        } elseif ($role == "assistant"){
                            echo '<label>Dentist:</label>';
                            if (($date_appointment==date('Y-m-d') && $start_time>=date('H:i:s')) || $date_appointment>date('Y-m-d')) {
                                echo '<span class="icon"><ion-icon name="create-outline"></ion-icon></span>';
                                echo '<select name="assistant_id">';
                                $all_dentists = getAllDentists();
                                echo '<option value="' . $appointment['dentist_id'] . '">' . $professional_name . '</option>';
                                foreach ($all_dentists as $dentist) {
                                    if($dentist['active_dentist'] == 1 && $appointment['dentist_id'] != $dentist['id']){
                                        echo '<option value="' . $dentist['id'] . '">' . $dentist['person_name'] . '</option>';
                                    }
                                }
                                echo '</select>';
                            } else {
                                echo '<select name="assistant_id" readonly>';
                                echo '<option value="' . $appointment['dentist_id'] . '">' . $professional_name . '</option>';
                                echo '</select>';
                            }
                        }
                        echo '</div>';
                        echo '<p class="warning">';
                            if (isset($_GET['message'])) {
                                $message = urldecode($_GET['message']);
                                $appointment_id = urldecode($_GET['appointment_id']);
                                if ($message=='professional unavailable' && $id_appointment==$appointment_id) {
                                    echo 'The professional already has an appointment at that time.';
                                } 
                            }
                        echo '</p>';

                        echo '<div class="input-box">';
                        echo '<label>Machines:</label>';
                        echo '<ul class="machines">';
                        $name_machines = getMachinesName($id_appointment);
                        if (empty($name_machines)) {
                            echo '<li>';
                            echo '<input class="list_item" type="type" name="machine" value="No machines were requested."readonly>';
                            echo '</li>';
                        }else{
                            foreach ($name_machines as $name_machine) {;
                                echo '<li>';
                                echo '<input class="list_item" type="type" name="machine" value="' . $name_machine['machine_name'] .' '.$name_machine['model'].'"readonly>';
                                echo '</li>';
                            }
                        }
                        echo '</ul>';
                        echo '</div>';
                        
                        if (($date_appointment==date('Y-m-d') && $start_time>=date('H:i:s')) || $date_appointment>date('Y-m-d')) {
                            echo '<button type="submit" class="btn" name="delete" id="btn_delete">Delete</button>';
                            echo '<button type="submit" class="btn" name="edit" id="btn_save">Save Changes</button>';
                        } elseif(getReport($id_appointment)==NULL && $_SESSION['role'] != 'assistant') {
                            //echo '<button type="submit" class="btn" name="edit_report" id="btn_edit">Edit Report</button>';
                            echo '<button type="submit" class="btn" name="add_report" id="btn_save">Add Report</button>';
                        }
                        echo '</form>';
                    }
                }

                $all_appointments = getAllAppointments($date_appointment);
                echo '<h4>Other appointments:</h4>';
                if (empty($all_appointments)) {
                    echo '<p>There are no scheduled appointments.</p>';
                } else {
                    foreach ($all_appointments as $appointment) {
                        $patient_name = getPatientName($appointment['patient_id']);
                        $assistant_name = getAssistantName($appointment['assistant_id']);
                        $dentist_name = getDentistName($appointment['dentist_id']);
                        $id_appointment = $appointment['appointment_id'];
                        $hours_appointment = getHoursAppointment($id_appointment);
                        $start_time = $hours_appointment['start_time'];
                        $end_time = $hours_appointment['end_time'];

                        echo '<form class="appointment-box" id="read" action="../php/appointments_functions.php?appointment_id='.$id_appointment.'&date_appointment='. $date_appointment.'" method="POST">';

                        echo '<div class="input-box">';
                        echo '<label>Dentist:</label>';
                        echo '<input type="type" name="date_appointment" value="' . $dentist_name . '"readonly>';
                        echo '</div>';

                        echo '<div class="input-box">';
                        echo '<label>Hour:</label>';
                        echo '<input type="type" name="start_time" value="' . $start_time . ' - ' . $end_time . '"readonly>';
                        echo '</div>';

                        echo '<div class="input-box">';
                        echo '<label>Patient:</label>';
                        echo '<input type="type" name="patient_id" value="' .  $patient_name . '"readonly>';
                        echo '</div>';

                        echo '<div class="input-box">';
                        echo '<label>Assistant:</label>';
                        echo '<input type="type" name="assistant_id" value="' . $assistant_name . '"readonly>';
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
