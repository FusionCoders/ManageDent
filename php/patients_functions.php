<?php 
include_once("../php/db_functions.php");

if (isset($_POST['create']) || isset($_POST['edit'])) {
    session_start();
    $idPat = $_SESSION['idPat'];
    $name = $_POST['name'];
    $tax_id = $_POST['tax_id'];
    $birth_date = $_POST['birth_date'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];

    if(isset($_POST['create'])){
        $_SESSION['namePat'] = $_POST['name'];
        $_SESSION['tax_idPat'] = $_POST['tax_id'];
        $_SESSION['birth_datePat'] = $_POST['birth_date'];
        $_SESSION['phone_numberPat'] = $_POST['phone_number'];
        $_SESSION['emailPat'] = $_POST['email'];
    }

    if (isset($_POST['create'])){
        $message1='add';
    }else{
        $message1 = $idPat;
    }
    try{

        $currentData = date('Y-m-d');
        if (strtotime($birth_date) > strtotime($currentData)) {
            $message = 1;
            header("Location: ../html/patientsEdit.php?messageError=".urlencode($message)."&message=" . urlencode($message1));
            exit();
        } elseif (checkTaxIdRepeatedPatient($tax_id)) {
            $message = 2;
            header("Location: ../html/patientsEdit.php?messageError=".urlencode($message)."&message=" . urlencode($message1));
            exit();
        } else {
            if (isset($_POST['edit'])){
                editPatient($_SESSION['idPat'], $name, $tax_id, $birth_date, $phone_number, $email);
            }else{
                createPacient($name, $tax_id, $birth_date, $phone_number, $email);
            }
            header("Location:../html/patients.php");
            cleanSec();
            exit();
        }

    } catch(PDOException $e) {
        $err_msg = $e -> getMessage();
        if (stripos($err_msg, "tax_id") !== false && stripos($err_msg, "UNIQUE") !== false) {
            $message = 2;
            header("Location: ../html/patientsEdit.php?messageError=".urlencode($message)."&message=" . urlencode($message1));
            exit();
        } elseif (stripos($err_msg, "check_tax_id") !== false){
            $message = 3;
            header("Location: ../html/patientsEdit.php?messageError=".urlencode($message)."&message=" . urlencode($message1));
            exit();
        } elseif(stripos($err_msg, "check_phone_number")) {
            $message = 4;
            header("Location: ../html/patientsEdit.php?messageError=".urlencode($message)."&message=" . urlencode($message1));
            exit();
        } elseif(stripos($err_msg, "check_empty_or_spaces")) {
            $message = 5;
            header("Location: ../html/patientsEdit.php?messageError=".urlencode($message)."&message=" . urlencode($message1));
            exit();
        } elseif (stripos($err_msg, "CHECK") !== false && stripos($err_msg, "check_email") !== false){
            $message = 10;
            header("Location: ../html/patientsEdit.php?messageError=".urlencode($message)."&message=" . urlencode($message1));
            exit();
        } else {
            echo $err_msg;
        }
    }

} elseif (isset($_POST['cancel'])) {
    cleanSec();
    header("Location:../html/patients.php");
    exit();
}

function cleanSec(){
    unset($_SESSION['namePat']);
    unset($_SESSION['tax_idPat']);
    unset($_SESSION['birth_datePat']);
    unset($_SESSION['phone_numberPat']);
    unset($_SESSION['emailPat']);
}

?>

