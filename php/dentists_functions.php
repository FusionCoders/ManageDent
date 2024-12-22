<?php 
include_once("../php/db_functions.php");

if (isset($_POST['create']) || isset($_POST['edit'])) {
    session_start();
    $idDen = $_SESSION['idDen'];
    $prefix = $_POST['prefix'];
    $name = $_POST['name'];
    $tax_id = $_POST['tax_id'];
    $birth_date = $_POST['birth_date'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $salary = $_POST['salary'];
    $officeNum = $_POST['officeNum'];
    $password = $_POST['password'];
    $start_time0 = $_POST['start_time0'];
    $start_time1 = $_POST['start_time1'];
    $start_time2 = $_POST['start_time2'];
    $start_time3 = $_POST['start_time3'];
    $start_time4 = $_POST['start_time4'];
    $end_time0 = $_POST['end_time0'];
    $end_time1 = $_POST['end_time1'];
    $end_time2 = $_POST['end_time2'];
    $end_time3 = $_POST['end_time3'];
    $end_time4 = $_POST['end_time4'];
    $photo = $_FILES['photo'];

    if(isset($_POST['create'])){
        $_SESSION['nameDen'] = $_POST['name'];
        $_SESSION['tax_idDen'] = $_POST['tax_id'];
        $_SESSION['birth_dateDen'] = $_POST['birth_date'];
        $_SESSION['phone_numberDen'] = $_POST['phone_number'];
        $_SESSION['emailDen'] = $_POST['email'];
        $_SESSION['salaryDen'] = $_POST['salary'];
        $_SESSION['active_dentist'] = 1;
        $_SESSION['officeDen'] = $_POST['officeNum'];
        $_SESSION['start_time0Den'] = $_POST['start_time0'];
        $_SESSION['start_time1Den'] = $_POST['start_time1'];
        $_SESSION['start_time2Den'] = $_POST['start_time2'];
        $_SESSION['start_time3Den'] = $_POST['start_time3'];
        $_SESSION['start_time4Den'] = $_POST['start_time4'];
        $_SESSION['end_time0Den'] = $_POST['end_time0'];
        $_SESSION['end_time1Den'] = $_POST['end_time1'];
        $_SESSION['end_time2Den'] = $_POST['end_time2'];
        $_SESSION['end_time3Den'] = $_POST['end_time3'];
        $_SESSION['end_time4Den'] = $_POST['end_time4'];
        $_SESSION['prefix'] = $prefix;
        $_SESSION['nameWithoutPrefix'] = $name;
    }

    if (isset($_POST['create'])){
        $message1='add';
    }else{
        $message1 = $idDen;
    }
    try{
        $arrayIn = array($start_time0, $start_time1, $start_time2, $start_time3, $start_time4);
        $arrayEnd = array($end_time0, $end_time1, $end_time2, $end_time3, $end_time4);

        $ids = [];
        $checkHour = false;
        for ($i = 0; $i < count($arrayIn); $i++){
            if($arrayIn[$i]==NULL || $arrayEnd[$i]==NULL){
                $ids[] = NULL;
            }else{
                $id = checkScheduleExistence($i+1, $arrayIn[$i], $arrayEnd[$i]);
                $ids[] = $id;
                if ($arrayIn[$i]>$arrayEnd[$i]){
                    $checkHour = true;
                }
            }
        }
        // Filtrar valores não nulos
        $array_without_null = array_filter($ids, function($value) {
            return !is_null($value);
        });
        // Filtrar valores nulos
        $array_null = array_filter($ids, function($value) {
            return is_null($value);
        });
        // Concatenar os valores nulos ao final do array
        $array_finalId = array_merge($array_without_null, $array_null);

        // Se o nome for vazio não se adiciona o prefixo (para ser detetado na restrição da base de dados)
        if (empty(trim($name))) {
            $nameWithPrefix = $name;
        } else {
            $nameWithPrefix = $prefix." ".$name;
        }

        $currentDate = date('Y-m-d');
        if (strtotime($birth_date) > strtotime($currentDate)) {
            $message = 1;
            header("Location: ../html/dentistsEdit.php?messageError=".urlencode($message)."&message=" . urlencode($message1));
            exit();
        } elseif (checkTaxIdRepeatedDentist($tax_id)) {
            $message = 2;
            header("Location: ../html/dentistsEdit.php?messageError=".urlencode($message)."&message=" . urlencode($message1));
            exit();
        }   elseif ($checkHour) {
            $message = 3;
            header("Location: ../html/dentistsEdit.php?messageError=".urlencode($message)."&message=" . urlencode($message1));
            exit();
        } else {
            if (isset($_POST['edit'])){
                if(empty($password)){
                    $hashedPassword = $_SESSION['passwordDen'];
                }else{
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                }
                editDentist($_SESSION['idDen'], $nameWithPrefix, $tax_id, $birth_date, $phone_number, $email, $salary, $officeNum, $hashedPassword, "../img/Med". $_SESSION['idDen'] .".png", $_SESSION['active_dentist'], $array_finalId[0], $array_finalId[1], $array_finalId[2], $array_finalId[3], $array_finalId[4]);
                if(!empty($photo)){
                    savePhoto($photo, $_SESSION['idDen']);
                }
            }else{
                $num = numberLinesDentist();
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $finalId = $num+1;
                createDentist($finalId, $nameWithPrefix, $tax_id, $birth_date, $phone_number, $email, $salary, $officeNum, $hashedPassword, "../img/Med". $finalId .".png", 1, $array_finalId[0], $array_finalId[1], $array_finalId[2], $array_finalId[3], $array_finalId[4]);
                savePhoto($photo, $finalId);
            }
            header("Location:../html/dentists.php");
            cleanSec();
            exit();
        }

    } catch(PDOException $e) {
        $err_msg = $e -> getMessage();
        if (stripos($err_msg, "tax_id") !== false && stripos($err_msg, "UNIQUE") !== false) {
            $message = 2;
            header("Location: ../html/dentistsEdit.php?messageError=".urlencode($message)."&message=" . urlencode($message1));
            exit();
        } elseif (stripos($err_msg, "check_tax_id") !== false){
            $message = 5;
            header("Location: ../html/dentistsEdit.php?messageError=".urlencode($message)."&message=" . urlencode($message1));
            exit();
        } elseif(stripos($err_msg, "check_phone_number")) {
            $message = 6;
            header("Location: ../html/dentistsEdit.php?messageError=".urlencode($message)."&message=" . urlencode($message1));
            exit();
        } elseif(stripos($err_msg, "check_empty_or_spaces")) {
            $message = 7;
            header("Location: ../html/dentistsEdit.php?messageError=".urlencode($message)."&message=" . urlencode($message1));
            exit();
        } elseif(stripos($err_msg, "minimum_salary")) {
            $message = 8;
            header("Location: ../html/dentistsEdit.php?messageError=".urlencode($message)."&message=" . urlencode($message1));
            exit();
        }elseif(stripos($err_msg, "Dentist")!== false && stripos($err_msg, "NOT NULL") !== false) {
            $message = 9;
            header("Location: ../html/dentistsEdit.php?messageError=".urlencode($message)."&message=" . urlencode($message1));
            exit();
        }elseif (stripos($err_msg, "CHECK") !== false && stripos($err_msg, "check_email") !== false){
            $message = 10;
            header("Location: ../html/dentistsEdit.php?messageError=".urlencode($message)."&message=" . urlencode($message1));
            exit();
        }else {
            echo $err_msg;
        }
    }

} elseif (isset($_POST['delete'])) {
    try {
        removeAddDentist();
        cleanSec();
        header("Location:../html/dentists.php");
        exit();
    } catch(PDOException $e) {
        $err_msg = $e -> getMessage();
        echo $err_msg;
    }
} elseif (isset($_POST['cancel'])) {
    cleanSec();
    header("Location:../html/dentists.php");
    exit();
}
function cleanSec(){
    unset($_SESSION['nameDen']);
    unset($_SESSION['tax_idDen']);
    unset($_SESSION['birth_dateDen']);
    unset($_SESSION['phone_numberDen']);
    unset($_SESSION['emailDen']);
    unset($_SESSION['salaryDen']);
    unset($_SESSION['active_dentist']);
    unset($_SESSION['officeDen']);
    unset($_SESSION['start_time0Den']);
    unset($_SESSION['start_time1Den']);
    unset($_SESSION['start_time2Den']);
    unset($_SESSION['start_time3Den']);
    unset($_SESSION['start_time4Den']);
    unset($_SESSION['end_time0Den']);
    unset($_SESSION['end_time1Den']);
    unset($_SESSION['end_time2Den']);
    unset($_SESSION['end_time3Den']);
    unset($_SESSION['end_time4Den']);
    unset($_SESSION['prefix']);
    unset($_SESSION['nameWithoutPrefix']);
}

function savePhoto($photo, $num_ref){
    // Verifique se o arquivo foi enviado corretamente
    if (isset($photo) && $photo['error'] === UPLOAD_ERR_OK) {
        // Obtenha informações do arquivo
        $fileTmpPath = $photo['tmp_name'];
        // Carrega a imagem original 
        $imageOriginal = imagecreatefrompng($fileTmpPath);
        // Obtém as dimensões da imagem original
        $widthOriginal = imagesx($imageOriginal);
        $heightOriginal = imagesy($imageOriginal);
        // Determina o tamanho do corte (usando a menor dimensão)
        $sizeCut = min($widthOriginal, $heightOriginal);
        // Calcula as coordenadas do retângulo de corte
        $x = 0;
        $y = 0;
        $widthCut = $sizeCut;
        $heightCut = $sizeCut;
        // Cria uma nova imagem com as dimensões do corte desejado
        $imageCut = imagecreatetruecolor($widthCut, $heightCut);
        // Copia a porção retangular da imagem original para a nova imagem
        imagecopyresampled($imageCut, $imageOriginal, 0, 0, $x, $y, $widthCut, $heightCut, $widthCut, $heightCut);
        // Mova o arquivo para o diretório de destino
        if (imagepng($imageCut, "../img/Med". $num_ref .".png")) {
            echo "The photo was uploaded and saved successfully.";
        } else {
            echo "An error occurred while saving the photo.";
        }
        // Liberta a memória ocupada pelas imagens
        imagedestroy($imageOriginal);
        imagedestroy($imageCut);
    } else {
        echo "An error occurred while saving the photo.";
    }
}
