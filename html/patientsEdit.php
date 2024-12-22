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
    <title>Patient Edit</title>
    <link rel="stylesheet" href="../css/style_menu.css">
    <link rel="stylesheet" href="../css/dentistsEdit.css">
</head>


<body class="main-body">
    <?php
        echo '<style>.sidebar .nav_menu .patients { color: ' . 'white' . '; }</style>';
    ?>
    <section class="main-section">
    <h1>Patients</h1>
        <?php 
            $aux = 1;
            if (isset($_GET['message'])) {
                $message = urldecode($_GET['message']);
                if ($message == 'add' && !isset($_GET['messageError'])){
                    echo '<h2>Add Patient</h2>';
                    $_SESSION['idPat'] = "";
                    $_SESSION['namePat'] = "";
                    $_SESSION['tax_idPat'] = "";
                    $_SESSION['birth_datePat'] = "";
                    $_SESSION['phone_numberPat'] = "";
                    $_SESSION['emailPat'] = "";
                    $aux = 0;
                }elseif($message == 'add' && isset($_GET['messageError'])){
                    echo '<h2>Add Patient</h2>';
                    $aux = 0;
                }else{
                    echo '<h2>Patient Data</h2>';
                    $patient = getAllDataPatient($message);
                    $_SESSION['idPat'] = $message;
                    $_SESSION['namePat'] = $patient['person_name'];
                    $_SESSION['tax_idPat'] = $patient['tax_id'];
                    $_SESSION['birth_datePat'] = $patient['birth_date'];
                    $_SESSION['phone_numberPat'] = $patient['phone_number'];
                    $_SESSION['emailPat'] = $patient['email'];
                }       
            }
        ?>
        <section class="inf">
        <form action="../php/patients_functions.php" method="POST">
        
        
            <div class="input-box">
                <span class="icon"><ion-icon name="create-outline"></ion-icon></span>
                <input class="name" type="text" name="name" value="<?php if ($aux==1|| isset($_GET['messageError'])){echo $_SESSION['namePat'];}?>"required>
                <label>Name</label>
                <p class="warning">
                    <?php 
                        if (isset($_GET['messageError'])) {
                            $message = urldecode($_GET['messageError']);
                            if ($message == 5) {
                                echo 'Invalid Name!';
                            }
                        }
                    ?>
                </p>
            </div>

            <div class="input-box">
                <span class="icon"><ion-icon name="create-outline"></ion-icon></span>
                <input type="number" name="tax_id" value="<?php if ($aux==1 || isset($_GET['messageError'])){echo $_SESSION['tax_idPat'];}?>" required>
                <label>Tax ID</label>
                <p class="warning">
                    <?php 
                        if (isset($_GET['messageError'])) {
                            $message = urldecode($_GET['messageError']);
                            if ($message == 2) {
                                echo 'Tax ID already used!';
                            } elseif($message == 3) {
                                echo 'Invalid Tax ID!';
                            }
                        }
                    ?>
                </p>
            </div>

            <div class="input-box">
                <input type="date" name="birth_date" value="<?php if ($aux==1 || isset($_GET['messageError'])){echo $_SESSION['birth_datePat'];}?>" required>
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
                <input type="number" name="phone_number" value="<?php if ($aux==1 || isset($_GET['messageError'])){echo $_SESSION['phone_numberPat'];}?>"required>
                <label>Phone Number</label>
                <p class="warning">
                    <?php 
                        if (isset($_GET['messageError'])) {
                            $message = urldecode($_GET['messageError']);
                            if ($message == 4) {
                                echo 'Invalid phone number!';
                            }
                        }
                    ?>
                </p>
            </div>

            <div class="input-box">
                <span class="icon"><ion-icon name="create-outline"></ion-icon></span>
                <input type="email" name="email" value="<?php if ($aux==1 || isset($_GET['messageError'])){echo $_SESSION['emailPat'];}?>" required>
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

            <button type="submit" name="<?php if ($aux==1){echo 'edit';}else{echo 'create';}?>" class="btn"><?php if ($aux==1){echo 'Save Changes';}else{echo 'Save Data';}?></button>
            <button type="submit" class="btn" name="cancel" id="btn_cancel" formnovalidate>Return</button>
        </form>
        </section>

    </section>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>