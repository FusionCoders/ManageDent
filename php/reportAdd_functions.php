<?php 
include_once("../php/db_functions.php");

if(isset($_POST['cancel'])) {
    $data_consulta = $_GET["date_appointment"];
    header("Location:../html/appointments.php?date_appointment=".urlencode($date_appointment));
    exit();
} elseif (isset($_POST['add_report'])) {
    $date_appointment = $_GET["date_appointment"];
    $appointment_id = $_GET["appointment_id"];
    $observations = $_POST["observations"];
    $procedure_id = $_POST["procedure_id"];
    $procedure = getProcedure($procedure_id);
    $specialtyProcedure = $procedure['specialty_id'];
    $dentist_id = getDentistAppointment($appointment_id);
    $specialtiesDentist = getSpecialtiesDentist2($dentist_id);
    $var=0;
    foreach($specialtiesDentist as $specialtyDentist) {
        if($specialtyDentist['specialty_id'] == $specialtyProcedure) {
            $var+=1;
        }
    }
    try {
        if($var!=0) {
            addReport($appointment_id, $procedure_id, $observations);
            header("Location:../html/appointments.php?date_appointment=".urlencode($date_appointment));
            exit();
        } else {
            $message = 'procedure unavailable';
            header("Location:../html/reportAdd.php?message=".urlencode($message)."&appointment_id=".urlencode($appointment_id));
            exit();
        }
    } catch(PDOException $e) {
        $err_msg = $e -> getMessage();
        echo $err_msg;
    }
    
}