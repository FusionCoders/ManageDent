<?php 
include_once("../php/db_functions.php");

if (isset($_POST['save']) || isset($_POST['edit'])) {
    $reference_number = $_POST["reference_number"];
    $name = $_POST["name"];
    $model = $_POST["model"];
    $brand = $_POST["Brand"];
    $newBrand = $_POST["NewBrand"];
    $photo = $_FILES['photo'];
    if (isset($_POST['save'])){
        session_start();
        $_SESSION['reference_number'] = $reference_number;
        $_SESSION['nameMac'] = $name;
        $_SESSION['model'] = $model;
        $_SESSION['active_machine'] = 1;
    }
    
    try {
        // Verifica se pelo menos um dos campos foi preenchido
        if (($brand=='Select') && empty(trim($newBrand))) {
            $messageError = "1";
            // Redireciona de volta ao formulário exibindo a message de erro
            if (isset($_POST['save'])){
                header("Location: ../html/machinesEdit.php?messageError=" . urlencode($messageError). "&message=" . urlencode('add'));
            }else{
                header("Location: ../html/machinesEdit.php?messageError=" . urlencode($messageError). "&message=" . urlencode($reference_number));
            }
        }
        if (!empty(trim($newBrand))){
            $finalBrand = saveBrand($newBrand); //Passar de nome para id e guardar
        } else {
            $finalBrand = $brand; //get id
        }
        if (isset($_POST['save'])){
            $_SESSION['brand_id'] = $finalBrand;
        }
        if (isset($_POST['save'])){    
            createMachine($reference_number, $name, $model, $finalBrand, 1,"../img/Maq". $reference_number .".png");
            savePhoto($photo, $reference_number);
        }else{
            if(!empty($foto)){
                editMachine($reference_number, $name, $model, $finalBrand, "../img/Maq". $reference_number .".png", $_SESSION['active_machine']);
                savePhoto($photo, $reference_number);
            }else{
                session_start();
                editMachine($reference_number, $name, $model, $finalBrand, $_SESSION['photoMac'], $_SESSION['active_machine']);
            }  
        }

        unset($_SESSION['reference_number']);
        unset($_SESSION['nameMac']);
        unset($_SESSION['model']);
        unset($_SESSION['brand_id']);
        header("Location:../html/machines.php");
        exit();
        
    } catch(PDOException $e) {
        $err_msg = $e -> getMessage();
        
        if (isset($_POST['save'])){
            $message1='add';
        }else{
            $message1 = $reference_number;
        }

        if (stripos($err_msg, "reference_number") !== false && stripos($err_msg, "UNIQUE") !== false) {
            $message = 1;
            header("Location: ../html/machinesEdit.php?error=".urlencode($message)."&message=" . urlencode($message1));
            exit();
        } elseif (stripos($err_msg, "check_name_empty_or_spaces") !== false){
            $message = 2;
            header("Location: ../html/machinesEdit.php?error=".urlencode($message)."&message=" . urlencode($message1));
            exit();
        } elseif(stripos($err_msg, "check_model_empty_or_spaces")) {
            $message = 3;
            header("Location: ../html/machinesEdit.php?error=".urlencode($message)."&message=" . urlencode($message1));
            exit();
        } elseif(stripos($err_msg, "Brand.brand_name") !== false && stripos($err_msg, "UNIQUE") !== false) {
            $message = 4;
            header("Location: ../html/machinesEdit.php?error=".urlencode($message)."&message=" . urlencode($message1));
            exit();
        } else {
            echo $err_msg;
            exit();
        }
    }

} elseif (isset($_POST['delete'])) {
    $reference_number = $_POST["reference_number"];
    try {
        removeAddMachine($reference_number);
        unset($_SESSION['reference_number']);
        unset($_SESSION['nameMac']);
        unset($_SESSION['model']);
        unset($_SESSION['brand_id']);
        header("Location:../html/machines.php");
        exit();
    } catch(PDOException $e) {
        $err_msg = $e -> getMessage();
        echo $err_msg;
    }
} elseif (isset($_POST['cancel'])) {
    unset($_SESSION['reference_number']);
    unset($_SESSION['nameMac']);
    unset($_SESSION['model']);
    unset($_SESSION['brand_id']);
    header("Location:../html/machines.php");
    exit();
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
        if (imagepng($imageCut, "../img/Maq". $num_ref .".png")) {
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
