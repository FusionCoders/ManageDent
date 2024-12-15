<?php 
include_once("../html/sidebar.php");
include_once("../php/db_functions.php");
include_once("../php/profile_functions.php");
?>
<!Doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Perfil</title>
    <link rel="stylesheet" href="../css/style_menu.css">
    <link rel="stylesheet" href="../css/profile.css">
</head>

<body class="main-body">
    <?php
        echo '<style>.sidebar .nav_menu .update { color: ' . 'white' . '; }</style>';
    ?>
    <section class="main-section">
        <h1>Update Profile</h1>
        <section id="personal_area">
            <h2>Personal Area</h2>
            <form action="../php/profile_functions.php" method="POST">

                <div class="input-box">
                    <span class="icon"><ion-icon name="create-outline"></ion-icon></span>
                    <input type="text" name="person_name" value="<?php echo $_SESSION['nameWithoutPrefix']?>" required>
                    <label>Name</label>
                    <p class="warning">
                    <?php 
                        if (isset($_GET['message'])) {
                            $message = urldecode($_GET['message']);
                            if ($message == 5) {
                                echo 'Invalid Name!';
                            } 
                        }
                    ?></p>
                </div>

                <div class="input-box">
                    <input id="input_birth" type="date" name="birth_date" value="<?php echo $_SESSION['birth_date']?>" required>
                    <label>Date of birth</label>
                    <p class="warning">
                    <?php 
                        if (isset($_GET['message'])) {
                            $message = urldecode($_GET['message']);
                            if ($message == 4) {
                                echo 'Invalid Date!';
                            } 
                        }
                    ?></p>
                </div>

                <div class="input-box">
                <span class="icon"><ion-icon name="create-outline"></ion-icon></span>
                    <input type="number" name="tax_id" value="<?php echo $_SESSION['tax_id']?>" required>
                    <label>Tax ID</label>
                    <p class="warning">
                    <?php 
                        if (isset($_GET['message'])) {
                            $message = urldecode($_GET['message']);
                            if ($message == 1 || $message == 6) {
                                echo 'Tax ID already used!';
                            } elseif($message == 2) {
                                echo 'Invalid Tax ID!';
                            }
                        }
                    ?></p>
                </div>

                <div class="input-box">
                    <span class="icon"><ion-icon name="create-outline"></ion-icon></span>
                    <input type="number" name="phone_number" value="<?php echo $_SESSION['phone_number']?>" required>
                    <label>Phone Number</label>
                    <p class="warning">
                    <?php 
                        if (isset($_GET['message'])) {
                            $message = urldecode($_GET['message']);
                            if ($message == 3) {
                                echo 'Invalid Phone number!';
                            } 
                        }
                    ?></p>
                </div>

                <div class="input-box">
                    <input type="email" name="email" value="<?php echo $_SESSION['email']?>" readonly>
                    <label>Email</label>
                </div>

                <button type="submit" class="btn" name="change_personal_area">Save Changes</button>
            </form>
        </section>

        <?php
        if ($_SESSION['role'] == "dentist") {
            echo '<section class="line"></section>';
            echo '<section id="specialties">';
            echo '<h2>Specialties</h2>';
    
            $specialties_dentist = getSpecialtiesDentist();
            foreach ($specialties_dentist as $specialty) {
                echo '<div class="specialty-box">';
                echo $specialty;
                echo '<form action="../php/profile_functions.php" method="POST">';
                echo '<input type="hidden" name="specialty" value="' . $specialty . '">';
                echo '<button type="submit" class="btn_specialty" name="remove_specialty">Remove</button>';
                echo '</form>';
                echo '</div>';
            }
    
            echo '<div class="specialty-box">';
            echo '<form action="../php/profile_functions.php" method="POST">';
            echo '<select name="specialty">';
            
            $specialties_all = getSpecialties();
            foreach ($specialties_all as $specialty) {
                echo '<option value="' . $specialty . '">' . $specialty . '</option>';
            }
            
            echo '</select>';
            echo '<button type="submit" class="btn_specialty" id="btn_remove" name="add_specialty">Add</button>';
            echo '</form>';
            echo '</div>';
    
            echo '</section>';
            echo '</section>';
        }
        ?>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>