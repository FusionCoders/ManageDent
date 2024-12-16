<?php 
include_once("../php/db_functions.php");

if (isset($_POST['show_reports'])) {
    $date_appointment = $_POST["date_appointment"];
    header("Location:../html/reports.php?date_appointment=".urlencode($date_appointment));
    exit();
} elseif(isset($_POST['return'])) {
    header("Location:../html/appointmentsMenu.php");
    exit();
} elseif (isset($_POST['edit'])) {
    $report_id = $_GET['report_id'];
    $observations = $_POST["observations"];
    $date_appointment = $_GET["date_appointment"];

    try {
        editReport($report_id, $observations);
        header("Location:../html/appointmentsMenu.php");
        exit();
    } catch(PDOException $e) {
        $err_msg = $e -> getMessage();
        echo $err_msg;
    }
} elseif (isset($_POST['delete'])) {
    $date_appointment = $_GET["date_appointment"];
    $report_id = $_GET['report_id'];

    try {
        deleteReport($report_id);
        header("Location:../html/reports.php?date_appointment=".urlencode($date_appointment));
        exit();
    } catch(PDOException $e) {
        $err_msg = $e -> getMessage();
        echo $err_msg;
    }
}
?>