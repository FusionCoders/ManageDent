<?php 
include_once("../php/db_functions.php");

if (isset($_POST['add_appointment'])) {
    $date_appointment = $_POST['date_appointment'];
    $dentist_id = $_POST['dentist_id'];
    $patient_id = $_POST['patient_id'];
    $assistant_id = $_POST['assistant_id'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $appointment_id = NULL;

    $all_machines = getAllMachines();
    $machines=array();
    foreach ($all_machines as $machine) {
        if(isset($_POST['machine'.$machine['reference_number']])) {
            $machine = $_POST['machine'.$machine['reference_number']];
            $machines[]=$machine;
        }
    }

    $var_den=0;
    $var_ass=0;
    $var_pat=0;
    $var_hour_den=0;
    $var_mac=0;

    try {
        $var_den = availabilityDentist($date_appointment, $dentist_id,  $start_time, $end_time, $appointment_id);
        $var_ass = availabilityAssistant($date_appointment, $assistant_id, $start_time, $end_time, $appointment_id);
        $var_pat = availabilityPatient($date_appointment, $patient_id,  $start_time, $end_time, $appointment_id);
        $var_hour_den = checkIfDentistWorks($dentist_id, $date_appointment, $start_time, $end_time);
        
        if($start_time<$end_time) {
            if ($var_hour_den==0){
                if ($var_den==0) {
                    if ($var_pat==0){
                        if ($var_ass==0) {
                            $machineToAdd=array();
                            foreach($machines as $machine) {
                                $var_mac = availabilityMachine($machine, $date_appointment, $start_time, $end_time);
                                if ($var_mac!=0) {
                                    $message = $machine;
                                    header("Location: ../html/appointmentsAdd.php?message=".urlencode($message));
                                    exit();
                                } else {
                                    $machineToAdd[] = $machine;
                                }
                            }
                            $appointmentAdded = addAppointment($date_appointment, $dentist_id, $patient_id, $assistant_id, $start_time, $end_time);
                            foreach($machineToAdd as $machineAdd) {
                                addMachineAppointment($appointmentAdded, $machineAdd);
                            }
                            header("Location:../html/appointments.php?date_appointment=".urlencode($date_appointment));
                            exit();
                        } else {
                            $message = 'assistant unavailable';
                        header("Location: ../html/appointmentsAdd.php?message=".urlencode($message));
                        exit();
                        }
                    } else{
                        $message = 'patient unavailable';
                        header("Location: ../html/appointmentsAdd.php?message=".urlencode($message));
                        exit();
                    }
                } else {
                    $message = 'dentist unavailable';
                    header("Location: ../html/appointmentsAdd.php?message=".urlencode($message));
                    exit();
                }
            } else {
                $message = 'dentist not working';
                header("Location: ../html/appointmentsAdd.php?message=".urlencode($message));
                exit();
            }
        } else {
            $message = 'incorrect schedule';
            header("Location: ../html/appointmentsAdd.php?message=".urlencode($message));
            exit();
        }

    } catch(PDOException $e) {
        $err_msg = $e -> getMessage();
        echo $err_msg;
        $line = $e -> getLine();
        echo $line;
    }
    
} elseif (isset($_POST['cancel'])) {
    header("Location:../html/appointments.php");
    exit();
}