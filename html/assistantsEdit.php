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
    <title>Assistants Edit</title>
    <link rel="stylesheet" href="../css/style_menu.css">
    <link rel="stylesheet" href="../css/dentistsEdit.css">
    <link rel="stylesheet" href="../css/assistantsEdit.css">
</head>

<body class="main-body">
    <?php
        echo '<style>.sidebar .nav_menu .assistants { color: ' . 'white' . '; }</style>';
    ?>
    <section class="main-section">
        <h1>Assistants</h1>
        <?php 
            $aux = 1;
            if (isset($_GET['message'])) {
                $_SESSION['schedule1_idAss'] = "";
                $_SESSION['schedule2_idAss'] = "";
                $_SESSION['schedule3_idAss'] = "";
                $_SESSION['schedule4_idAss'] = "";
                $_SESSION['schedule5_idAss'] = "";
                // Recupere a message e decodifique-a
                $message = urldecode($_GET['message']);
                if ($message == 'add' && !isset($_GET['messageError'])){
                    echo '<h2>Add Assistant</h2>';
                    $_SESSION['idAss'] = "";
                    $_SESSION['nameAss'] = "";
                    $_SESSION['tax_idAss'] = "";
                    $_SESSION['birth_dateAss'] = "";
                    $_SESSION['phone_numberAss'] = "";
                    $_SESSION['emailAss'] = "";
                    $_SESSION['salaryAss'] = "";
                    $_SESSION['active_assistant'] = "";
                    $_SESSION['prefix'] = "";
                    $_SESSION['nameWithoutPrefix'] = "";
                    $aux = 0;
                }elseif($message == 'add' && isset($_GET['messageError'])){
                    echo '<h2>Add Assistant</h2>';
                    $aux = 0;
                }else{
                    echo '<h2>Assistant Data</h2>';
                    $assistant = getAssistantData($message);
                    $_SESSION['idAss'] = $message;
                    $_SESSION['nameAss'] = $assistant['person_name'];
                    $_SESSION['tax_idAss'] = $assistant['tax_id'];
                    $_SESSION['birth_dateAss'] = $assistant['birth_date'];
                    $_SESSION['phone_numberAss'] = $assistant['phone_number'];
                    $_SESSION['emailAss'] = $assistant['email'];
                    $_SESSION['salaryAss'] = $assistant['salary'];
                    $_SESSION['passwordAss'] = $assistant['assistant_password'];
                    $_SESSION['active_assistant'] = $assistant['active_assistant'];
                    $_SESSION['schedule1_idAss'] = $assistant['schedule1_id'];
                    $_SESSION['schedule2_idAss'] = $assistant['schedule2_id'];
                    $_SESSION['schedule3_idAss'] = $assistant['schedule3_id'];
                    $_SESSION['schedule4_idAss'] = $assistant['schedule4_id'];
                    $_SESSION['schedule5_idAss'] = $assistant['schedule5_id'];
                    $prefix = strstr($assistant['person_name'], ' ', true);
                    $_SESSION['prefix'] = $prefix;
                    $spacePosition = strpos($assistant['person_name'], ' ');
                    $nameWithoutPrefix = substr($assistant['person_name'], $spacePosition + 1);
                    $_SESSION['nameWithoutPrefix'] = $nameWithoutPrefix;
                }       
            }
        ?>
        <section class="inf">
        <form action="../php/assistants_functions.php" method="POST" enctype="multipart/form-data">

            <div class="input-box">
                <span class="icon"><ion-icon name="create-outline"></ion-icon></span>
                <input class="name" type="text" name="name" value="<?php if ($aux==1|| isset($_GET['messageError'])){echo $_SESSION['nameWithoutPrefix'];}?>"required>
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
                <input type="number" name="tax_id" value="<?php if ($aux==1 || isset($_GET['messageError'])){echo $_SESSION['tax_idAss'];}?>" required>
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
                <input type="date" name="birth_date" value="<?php if ($aux==1 || isset($_GET['messageError'])){echo $_SESSION['birth_dateAss'];}?>" required>
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
                <input type="number" name="phone_number" value="<?php if ($aux==1 || isset($_GET['messageError'])){echo $_SESSION['phone_numberAss'];}?>"required>
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
                <input type="email" name="email" value="<?php if ($aux==1 || isset($_GET['messageError'])){echo $_SESSION['emailAss'];}?>" required>
                <label>Email</label>
                <p class="warning">
                    <?php 
                        if (isset($_GET['messageError'])) {
                            $message = urldecode($_GET['messageError']);
                            if ($message == 10) {
                                echo 'Enter a valid email!';
                            }
                        }
                    ?>
                </p>
            </div>

            <div class="input-box">
                <span class="icon"><ion-icon name="create-outline"></ion-icon></span>   
                <input type="password" name="password" <?php if ($aux==0){echo 'required';}?>>
                <label><?php if ($aux==1){echo 'New Password';}else{echo 'Password';}?></label>
            </div>

            <div class="input-box">
                <span class="icon"><ion-icon name="create-outline"></ion-icon></span>   
                <input type="number" name="salary" value="<?php if ($aux==1 || isset($_GET['messageError'])){echo $_SESSION['salaryAss'];}?>" required>
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

            <div class="input-box" id="schedule-box"> 
                <label class="name">Schedule</label> 
                <div class="days">
                    <?php 
                        $daysWeek = array("Monday: ", "Tuesday: ", "Wednesday: ", "Thursday: ", "Friday: ");
                        $schedule = getHoursAssistant(); // Obter horas de inicio e de fim de horarios de trabalho do assistant
                        $p=0;
                        for ($i = 0; $i < count($daysWeek); $i++) {
                            echo '<div class="hours">';
                            echo '<label>'. $daysWeek[$i] .'</label>';
                            $numberExists = false;
                            foreach ($schedule as $day) {
                                $week_day = explode(',', $day['week_day']);
                                if (in_array($i+1, $week_day)) {
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
                            } elseif($message == 3) {
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
                    if($_SESSION['active_assistant']==1){
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