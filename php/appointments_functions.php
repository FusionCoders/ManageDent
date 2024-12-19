<?php 
include_once("../php/db_functions.php");

if (isset($_POST['show_appointments'])) {
    $date_appointment = $_POST["date_appointment"];
    header("Location:../html/appointments.php?date_appointment=".urlencode($date_appointment));
    exit();
} elseif(isset($_POST['return'])) {
    header("Location:../html/appointmentsMenu.php");
    exit();
} elseif (isset($_POST['edit'])) {
    $appointment_id = $_GET['appointment_id'];
    $date_appointment = $_GET['date_appointment'];
    $patient_id = $_POST['patient_id'];
    $professional_id = $_POST['assistant_id'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    try {
        if($start_time<$end_time) {
            $var = availabilityProfessional($date_appointment, $professional_id, $start_time, $end_time, $appointment_id);
            if ($var==0) {
                editAppointment($date_appointment, $appointment_id, $patient_id, $professional_id, $start_time, $end_time);
                header("Location:../html/appointmentsMenu.php");
                exit();
            } else {
                $message = 'professional unavailable';
                header("Location: ../html/appointments.php?message=".urlencode($message)."&date_appointment=".urlencode($date_appointment)."&appointment_id=".urlencode($appointment_id));
                exit();
            }
        } else {
            $message = 'schedule incorrect';
            header("Location: ../html/appointments.php?message=".urlencode($message)."&date_appointment=".urlencode($date_appointment)."&appointment_id=".urlencode($appointment_id));
            exit();
        }
    } catch(PDOException $e) {
        $err_msg = $e -> getMessage();
        echo $err_msg;
        $line = $e -> getLine();
        echo $line;
    }
} elseif (isset($_POST['delete'])) {
    $date_appointment = $_GET["date_appointment"];
    $appointment_id = $_GET['appointment_id'];

    try {
        removeAppointment($appointment_id);
        header("Location:../html/appointments.php?date_appointment=".urlencode($date_appointment));
        exit();
    } catch(PDOException $e) {
        $err_msg = $e -> getMessage();
        echo $err_msg;
    }
} elseif (isset($_POST['add'])) {
    header("Location:../html/appointmentsAdd.php");
    exit();
} elseif (isset($_POST['edit_report'])) {

} elseif (isset($_POST['add_report'])) {
    $date_appointment = $_GET["date_appointment"];
    $appointment_id = $_GET['appointment_id'];
    header("Location:../html/reportAdd.php?appointment_id=".urlencode($appointment_id)."&date_appointment=".urlencode($date_appointment));
    exit();
}
?>