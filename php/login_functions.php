<?php 
include_once("../php/db_functions.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    authenticateUser($email, $password);
}

function authenticateUser($email, $password){
    $storedPasswordHashDentist = recoverHashPasswordDentist($email);
    $storedPasswordHashAssistant = recoverHashPasswordAssistant($email);
    // Validação password
    if (password_verify($password, $storedPasswordHashDentist)) {
        if ($email == 'diogobastos@gmail.com'){
            createSession("admin", $email);
            header("Location:../html/menuAdm.php");
        } else{
            if(dentistValid($email)){
                createSession("dentist", $email);
                header("Location:../html/menuDenAss.php");
            }else{
                $message = 'deleted';
                header("Location: ../html/login.php?message=".urlencode($message));
            }
        } 
        exit();
    } else if (password_verify($password, $storedPasswordHashAssistant)){
        if(assistantValid($email)){
            header("Location:../html/menuDenAss.php");
            createSession("assistant", $email);
        }else{
            $message = 'deleted';
            header("Location: ../html/login.php?message=".urlencode($message));
            exit();
        }
        exit();
    } else {
        $message = 'show';
        header("Location: ../html/login.php?message=".urlencode($message));
        exit();
    }
}

function warning(){
    echo 'Invalid username or password.';
}

function warning1(){
    echo 'User is inactive.';
}
?>