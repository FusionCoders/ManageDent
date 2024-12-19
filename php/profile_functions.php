<?php 
include_once("../php/db_functions.php");

if (isset($_POST['change_personal_area'])) {
    $email = $_POST["email"];
    $person_name = $_POST["person_name"];
    $birth_date = $_POST["birth_date"];
    $tax_id = $_POST["tax_id"];
    $phone_number = $_POST["phone_number"];

    try {
        $currentDate = date('Y-m-d');
        if (strtotime($birth_date) > strtotime($currentDate)) {
            $message = 4;
            header("Location: ../html/profile.php?message=".urlencode($message));
            exit();
        } elseif (CheckRepeatedTaxId($tax_id)) {
            $message = 6;
            header("Location: ../html/profile.php?message=".urlencode($message));
            exit();
        } else {
            editPersonalArea($email, $person_name, $birth_date, $tax_id, $phone_number);
            session_start();
            $role = $_SESSION['role'];
            if ($role == "admin"){
                header("Location:../html/menuAdm.php");
            }else{
                header("Location:../html/menuDenAss.php");
            }
            exit();
        }
    } catch(PDOException $e) {
        $err_msg = $e -> getMessage();

        if (stripos($err_msg, "tax_id") !== false && stripos($err_msg, "UNIQUE") !== false) {
            $message = 1;
            header("Location: ../html/profile.php?message=".urlencode($message));
            exit();
        } elseif (stripos($err_msg, "check_tax_id") !== false){
            $message = 2;
            header("Location: ../html/profile.php?message=".urlencode($message));
            exit();
        } elseif(stripos($err_msg, "check_phone_number")) {
            $message = 3;
            header("Location: ../html/profile.php?message=".urlencode($message));
            exit();
        } elseif(stripos($err_msg, "check_empty_or_spaces")) {
            $message = 5;
            header("Location: ../html/profile.php?message=".urlencode($message));
            exit();
        } else {
            echo $err_msg;
        }
    }
} elseif (isset($_POST['remove_specialty'])) {
    $specialty = $_POST["specialty"];

    try {
        removeSpecialty($specialty);
        header("Location:../html/profile.php");
        exit();
    } catch(PDOException $e) {
        $err_msg = $e -> getMessage();
        echo $err_msg;
    }
} elseif (isset($_POST['add_specialty'])) {
    $specialty = $_POST["specialty"];

    try {
        addSpecialty($specialty);
        header("Location:../html/profile.php");
        exit();
    } catch(PDOException $e) {
        $err_msg = $e -> getMessage();
        echo $err_msg;
    }
}


