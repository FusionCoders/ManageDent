<?php
include_once("../php/db_functions.php");
include_once("../html/sidebar.php");
?>
<!Doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dentists Edit</title>
    <link rel="stylesheet" href="../css/style_menu.css">
    <link rel="stylesheet" href="../css/dentistsEdit.css">
</head>

<body class="main-body">
    <?php
        echo '<style>.sidebar .nav_menu .dentists { color: ' . 'white' . '; }</style>';
    ?>
    <section class="main-section">
        <h1>Dentists</h1>
        <?php 
            $aux = 1;
            if (isset($_GET['message'])) {
                $_SESSION['schedule1_idDen'] = "";
                $_SESSION['schedule2_idDen'] = "";
                $_SESSION['schedule3_idDen'] = "";
                $_SESSION['schedule4_idDen'] = "";
                $_SESSION['schedule5_idDen'] = "";
                // Recupere a message e decodifique-a
                $message = urldecode($_GET['message']);
                if ($message == 'add' && !isset($_GET['messageError'])){
                    echo '<h2>Add Dentist</h2>';
                    $_SESSION['idDen'] = "";
                    $_SESSION['nameDen'] = "";
                    $_SESSION['tax_idDen'] = "";
                    $_SESSION['birth_dateDen'] = "";
                    $_SESSION['phone_numberDen'] = "";
                    $_SESSION['emailDen'] = "";
                    $_SESSION['salaryDen'] = "";
                    $_SESSION['active_dentist'] = "";
                    $_SESSION['officeDen'] = "";
                    $_SESSION['prefix'] = "";
                    $_SESSION['nameWithoutPrefix'] = "";
                    $aux = 0;
                }elseif($message == 'add' && isset($_GET['messageError'])){
                    echo '<h2>Add Dentist</h2>';
                    $prefix = $_SESSION['prefix'];
                    $aux = 0;
                }else{
                    echo '<h2>Dentist Data</h2>';
                    $dentist = getDataDentist($message);
                    $_SESSION['idDen'] = $message;
                    $_SESSION['nameDen'] = $dentist['person_name'];
                    $_SESSION['tax_idDen'] = $dentist['tax_id'];
                    $_SESSION['birth_dateDen'] = $dentist['birth_date'];
                    $_SESSION['phone_numberDen'] = $dentist['phone_number'];
                    $_SESSION['emailDen'] = $dentist['email'];
                    $_SESSION['salaryDen'] = $dentist['salary'];
                    $_SESSION['passwordDen'] = $dentist['dentist_password'];
                    $_SESSION['active_dentist'] = $dentist['active_dentist'];
                    $_SESSION['officeDen'] = $dentist['office'];
                    $_SESSION['schedule1_idDen'] = $dentist['schedule1_id'];
                    $_SESSION['schedule2_idDen'] = $dentist['schedule2_id'];
                    $_SESSION['schedule3_idDen'] = $dentist['schedule3_id'];
                    $_SESSION['schedule4_idDen'] = $dentist['schedule4_id'];
                    $_SESSION['schedule5_idDen'] = $dentist['schedule5_id'];
                    $prefix = strstr($dentist['person_name'], ' ', true);
                    $_SESSION['prefix'] = $prefix;
                    $spacePosition = strpos($dentist['person_name'], ' ');
                    $nameWithoutPrefix = substr($dentist['person_name'], $spacePosition + 1);
                    $_SESSION['nameWithoutPrefix'] = $nameWithoutPrefix;
                }       
            }
        ?>
        <section class="inf">
        <form action="../php/dentists_functions.php" method="POST" enctype="multipart/form-data">
        
        
            <div class="input-box">
                <span class="icon"><ion-icon name="create-outline"></ion-icon></span>
                <div class="inside-box">
                    <div class="check-box">
                        <input type="radio" name="prefix" value="Dr."<?php if ($aux==1 || isset($_GET['messageError'])){if ($prefix == "Dr."){echo 'checked';}}else{echo 'required';}?>> Dr.
                        <input type="radio" name="prefix" value="Dra."<?php if ($aux==1 || isset($_GET['messageError'])){if ($prefix == "Dra."){echo 'checked';}}else{echo 'required';}?>> Dra.
                </div> 
                    <input class="name" type="text" name="name" value="<?php if ($aux==1|| isset($_GET['messageError'])){echo $_SESSION['nameWithoutPrefix'];}?>"required>
                </div>
                <label>Name</label>
                <p class="warning">
                    <?php 
                        if (isset($_GET['messageError'])) {
                            $message = urldecode($_GET['messageError']);
                            if ($message == 7) {
                                echo 'Invalid Name!';
                            }
                        }
                    ?>
                </p>
            </div>

            <div class="input-box">
                <span class="icon"><ion-icon name="create-outline"></ion-icon></span>
                <input type="number" name="tax_id" value="<?php if ($aux==1 || isset($_GET['messageError'])){echo $_SESSION['tax_idDen'];}?>" required>
                <label>Tax ID</label>
                <p class="warning">
                    <?php 
                        if (isset($_GET['messageError'])) {
                            $message = urldecode($_GET['messageError']);
                            if ($message == 2) {
                                echo 'Tax ID already used!';
                            } elseif($message == 5) {
                                echo 'Invalid Tax ID!';
                            }
                        }
                    ?>
                </p>
            </div>

            <div class="input-box">
                <input type="date" name="birth_date" value="<?php if ($aux==1 || isset($_GET['messageError'])){echo $_SESSION['birth_dateDen'];}?>" required>
                <label>Date of Birth</label>
                <p class="warning">
                    <?php 
                        if (isset($_GET['messageError'])) {
                            $message = urldecode($_GET['messageError']);
                            if ($message == 1) {
                                echo 'Invalid Date!';
                            }
                        }
                    ?>
                </p>
            </div>

            <div class="input-box">
                <span class="icon"><ion-icon name="create-outline"></ion-icon></span>
                <input type="number" name="phone_number" value="<?php if ($aux==1 || isset($_GET['messageError'])){echo $_SESSION['phone_numberDen'];}?>"required>
                <label>Phone Number</label>
                <p class="warning">
                    <?php 
                        if (isset($_GET['messageError'])) {
                            $message = urldecode($_GET['messageError']);
                            if ($message == 6) {
                                echo 'Invalid phone number!';
                            }
                        }
                    ?>
                </p>
            </div>

            <div class="input-box">
                <span class="icon"><ion-icon name="create-outline"></ion-icon></span>
                <input type="email" name="email" value="<?php if ($aux==1 || isset($_GET['messageError'])){echo $_SESSION['emailDen'];}?>" required>
                <label>Email</label>
            </div>

            <div class="input-box">
                <span class="icon"><ion-icon name="create-outline"></ion-icon></span>   
                <input type="password" name="password" <?php if ($aux==0){echo 'required';}?>>
                <label><?php if ($aux==1){echo 'New Password';}else{echo 'Password';}?></label>
            </div>

            <div class="input-box">
                <span class="icon"><ion-icon name="create-outline"></ion-icon></span>   
                <input type="number" name="salary" value="<?php if ($aux==1 || isset($_GET['messageError'])){echo $_SESSION['salaryDen'];}?>" required>
                <label>Salary (Eur)</label>
                <p class="warning">
                    <?php 
                        if (isset($_GET['messageError'])) {
                            $message = urldecode($_GET['messageError']);
                            if ($message == 8) {
                                echo 'Minimum wage of 760 euros!';
                            }
                        }
                    ?>
                </p>
            </div>
            
            <div class="input-box" id="office">
                <span class="icon"><ion-icon name="create-outline"></ion-icon></span>
                <div class="inside-box">   
                    <input type="radio" name="officeNum" value="1" <?php if ($aux==1 || isset($_GET['messageError'])){if ($_SESSION['officeDen'] == 1){echo 'checked';}}else{echo 'required';}?>> 1
                    <input type="radio" name="officeNum" value="2" <?php if ($aux==1 || isset($_GET['messageError'])){if ($_SESSION['officeDen'] == 2){echo 'checked';}}else{echo 'required';}?>> 2
                    <input type="radio" name="officeNum" value="3" <?php if ($aux==1 || isset($_GET['messageError'])){if ($_SESSION['officeDen'] == 3){echo 'checked';}}else{echo 'required';}?>> 3
                    <input type="radio" name="officeNum" value="4" <?php if ($aux==1 || isset($_GET['messageError'])){if ($_SESSION['officeDen'] == 4){echo 'checked';}}else{echo 'required';}?>> 4
                    <input type="radio" name="officeNum" value="5" <?php if ($aux==1 || isset($_GET['messageError'])){if ($_SESSION['officeDen'] == 5){echo 'checked';}}else{echo 'required';}?>> 5
                <label>Office Number</label>
                </div>
            </div>

            <div class="input-box" id="schedule-box"> 
                <label class="name">Schedule</label> 
                <div class="days">
                    <?php 
                        $daysWeek = array("Monday: ", "Tuesday: ", "Wednesday: ", "Thursday: ", "Friday: ");
                        $schedule = getHoursDentist(); // Obter horas de inicio e de fim de horarios de trabalho do médico
                        $p=0;
                        for ($i = 0; $i < count($daysWeek); $i++) {
                            echo '<div class="hours">';
                            echo '<label>'. $daysWeek[$i] .'</label>';
                            $numberExists = false;
                            foreach ($schedule as $day) {
                                $day_week = explode(',', $day['week_day']);
                                if (in_array($i+1, $day_week)) {
                                    $numberExists = true;
                                    break; // Encerra o loop assim que encontrar o número
                                }
                            }
                            if ($numberExists) {
                                echo '<input type="time" name="start_time'.$i.'" value="' .$schedule[$i-$p]['start_time']. '">';
                                echo '<p> - </p>';
                                echo '<input type="time" name="end_time'.$i.'" value="' .$schedule[$i-$p]['end_time']. '">';
                            }else{
                                $p=$p+1;
                                echo '<input type="time" name="start_time'.$i.'" >';
                                echo '<p> - </p>';
                                echo '<input type="time" name="end_time'.$i.'">';
                            }
                            echo '</div>';
                        }
                    ?>
                </div>
                <p class="warning">
                    <?php 
                        if (isset($_GET['messageError'])) {
                            $message = urldecode($_GET['messageError']);
                            if ($message == 9) {
                                echo 'At least 3 working days required!';
                            } elseif ($message == 3) {
                                echo 'Entry hours later than exit hours!';
                            }                            
                        }
                    ?>
                </p>
            </div>
                
            <div class="input-box" id="photoDiv">
                <label><?php if ($aux==1){echo 'New Photo';}else{echo 'Photo';}?></label>
                <input type="file" name="photo" class="photo" accept="image/png" <?php if ($aux==0){echo 'required';}?>>
            </div>

            <button type="submit" name="<?php if ($aux==1){echo 'edit';}else{echo 'create';}?>" class="btn"><?php if ($aux==1){echo 'Save Changes';}else{echo 'Save Data';}?></button>
            <button type="submit" class="btn" name="cancel" id="btn_cancel" formnovalidate>Return</button>
            <?php if ($aux==1){
                    if($_SESSION['active_dentist']==1){
                        echo '<button type="submit" class="btn" name="delete" id="btn_delete">Disable</button>';
                    }else{
                        echo '<button type="submit" class="btn" name="delete" id="btn_delete">Enable</button>';
                    }
                }
            ?>
        </form>
        </section>

    </section>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>