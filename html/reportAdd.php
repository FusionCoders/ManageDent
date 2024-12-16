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
    <link rel="stylesheet" href="../css/reportAdd.css">
</head>

<body class="main-body">
    <?php
        echo '<style>.sidebar .nav_menu .appointments { color: ' . 'white' . '; }</style>';
    ?>
    <section class="main-section">
        <h1>Appointments</h1>
        <section id="content">    
            <h2>Add Report</h2>
            <?php 
            if (isset($_GET['appointment_id'])) {
                $appointment_id = urldecode($_GET['appointment_id']);
                $appointment = getAppointment($appointment_id);
                $date_appointment = $appointment['date_appointment'];
                $patient_id = $appointment['patient_id'];
                $name_patient = getPatientName($patient_id);
                $assistant_id = $appointment['assistant_id'];
                $name_assistant = getAssistantName($assistant_id);
                $schedule_id = $appointment['schedule_id'];
                $hours = getHoursAppointment($appointment_id);
                $start_time = $hours['start_time'];
                $end_time = $hours['end_time'];
                $name_machines = getMachinesName($appointment_id);
            }

            
            echo '<form action="../php/reportAdd_functions.php?appointment_id='.$appointment_id.'&date_appointment='.$date_appointment.'" method="POST">'
            ?>
                <div class="input-box">
                    <input type="date" name="date_appointment" value="<?php echo $date_appointment?>" readonly>
                    <label>Date</label>
                </div>

                <div class="input-box">
                    <label>Hour</label>
                    <div class="hours">
                        <input type="time" name="start_time" value="<?php echo $start_time?>"readonly>
                        <p> - </p>
                        <input type="time" name="end_time" value="<?php echo $end_time?>"readonly>
                    </div>
                </div>

                <div class="input-box">
                    <input type="name" name="patient" value="<?php echo $name_patient?>" readonly>
                    <label>Patient</label>
                </div>

                <div class="input-box">
                    <input type="name" name="assistant" value="<?php echo $name_assistant?>" readonly>
                    <label>Assistant</label>
                </div>

                <div class="input-box">
                    <label>Machines:</label>
                    <ul class="machine">
                    <?php
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
                    ?>
                    </ul>
                </div>

                <div class="input-box">
                    <label>Procedure:</label>
                    <span class="icon"><ion-icon name="create-outline"></ion-icon></span>
                        <select name="procedure_id">
                        <?php
                        $all_procedures = getAllProcedures();
                        foreach ($all_procedures as $procedure) {
                            echo '<option value="' . $procedure['id'] . '">' . $procedure['name'] . '</option>';
                        }
                        ?>
                        </select>                 
                </div>

                <?php
                echo '<p class="warning">';
                if (isset($_GET['message'])) {
                    $message = urldecode($_GET['message']);
                    if ($message=='procedure unavailable') {
                        echo 'This procedure is not included in the dentist\'s specialty!';
                    } 
                }
                echo '</p>';
                ?>

                <div class="input-box">
                    <label>Observations:</label>
                    <span class="icon"><ion-icon name="create-outline"></ion-icon></span>
                    <textarea name="observations"></textarea>
                </div>

                <button type="submit" class="btn" name="cancel" id="btn_cancel" formnovalidate>Return</button>
                <button type="submit" class="btn" name="add_report" id="btn_add">Add</button>
            </form>

            <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
            <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
        </section>
    </section>

   
</body>

</html>