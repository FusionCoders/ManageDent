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
    <title>Machines Edit</title>
    <link rel="stylesheet" href="../css/style_menu.css">
    <link rel="stylesheet" href="../css/machinesEdit.css">
</head>

<body class="main-body">
    <?php
        echo '<style>.sidebar .nav_menu .machines { color: ' . 'white' . '; }</style>';
    ?>
    <section class="main-section">
        <h1>Machines</h1>
        <?php 
            $messageError = '0';
            $machine = '0';
            if (isset($_GET['message']) and !isset($_GET['messageError'])) {
                // Recupere a message e decodifique-a
                $message = urldecode($_GET['message']);
                if ($message == 'add'){
                    echo '<h2>Add Machine/Equipment</h2>';
                    if (!isset($_GET['error'])){
                        $_SESSION['reference_number'] = "";
                        $_SESSION['nameMac'] = "";
                        $_SESSION['model'] = "";
                        $_SESSION['brand_id'] = "";
                        $_SESSION['active_machine'] = "";
                    }
                }else{
                    echo '<h2>Machine/Equipment Data</h2>';
                    $machine = getDataMachine($message);
                    $_SESSION['reference_number'] = $machine['reference_number'];
                    $_SESSION['nameMac'] = $machine['machine_name'];
                    $_SESSION['model'] = $machine['model'];
                    $_SESSION['brand_id'] = $machine['brand_id'];
                    $_SESSION['active_machine'] = $machine['active_machine'];
                    $currentBrand = getCurrentBrand();
                }       
            }elseif (isset($_GET['messageError'])){
                echo '<h2>Add Machine/Equipment</h2>';
                $messageError = urldecode($_GET['messageError']);
                $machine = '1';
                
            }
        ?>
        <section class="inf-machines">
        <form action="../php/machines_functions.php" method="POST" enctype="multipart/form-data">
            <div class="input-box">
                <input type="number" name="reference_number" value="<?php if ($machine!='0' || isset($_GET['error'])){echo $_SESSION['reference_number'];}?>" <?php if ($machine!='0'){echo 'readonly';}else{echo 'required';}?>>
                <label>Reference Number</label>
                <p class="warning">
                    <?php 
                        if (isset($_GET['error'])) {
                            $message = urldecode($_GET['error']);
                            if ($message == 1) {
                                echo 'Reference number already used!!';
                            }
                        }
                    ?></p>
            </div>

            <div class="input-box">
                <span class="icon"><ion-icon name="create-outline"></ion-icon></span>
                <input type="text" name="name" value="<?php if ($machine!='0' || isset($_GET['error'])){echo $_SESSION['nameMac'];}?>" required>
                <label>Name</label>
                <p class="warning">
                    <?php 
                        if (isset($_GET['error'])) {
                            $message = urldecode($_GET['error']);
                            if ($message == 2) {
                                echo 'Invalid Name!';
                            }
                        }
                    ?></p>
            </div>

            <div class="input-box">
                <span class="icon"><ion-icon name="create-outline"></ion-icon></span>
                <input type="text" name="model" value="<?php if ($machine!='0' || isset($_GET['error'])){echo $_SESSION['model'];}?>" required>
                <label>Model</label>
                <p class="warning">
                    <?php 
                        if (isset($_GET['error'])) {
                            $message = urldecode($_GET['error']);
                            if ($message == 3) {
                                echo 'Invalid Model!';
                            }
                        }
                    ?></p>
            </div>
            
            <label class="label_brand">Brand</label>
            <div class="add-box">
                <select class="select" name="Brand" id="brandSelect">
                    <?php
                        $result = getBrands();
                        if ($machine!='0'){
                            if (isset($_GET['message']) and isset($_GET['messageError'])){
                                echo '<option value = "Select">Select a Brand</option>';
                            }else{
                                echo '<option value = "'. $_SESSION['brand_id'] .'">'. $currentBrand['brand_name'] .'</option>';
                            }         
                        }else{
                            echo '<option value = "Select">Select a Brand</option>';
                        }
                        for ($i = 0; $i < count($result); $i++) {
                            if ($currentBrand['brand_name'] != $result[$i]['brand_name']){
                                echo '<option value = "'. $result[$i]['id'] .'">'. $result[$i]['brand_name'] .'</option>';
                            }
                        }
                    ?>
                </select>
                <input type="text" name="NewBrand" id="newBrandInput" placeholder="Enter a new brand">
            </div>
            <p class="warning_brand">
                <?php 
                    if ($messageError == "1") {
                        echo 'Select a brand, if not enter a new one!';
                    }elseif (isset($_GET['error'])){
                        $message = urldecode($_GET['error']);
                        if ($message == 4) {
                            echo 'You are trying to add a brand that already exists!';
                        }
                    }
                ?>
            </p>
            <div class="input-box" id="photoDiv">
                <label><?php if ($machine!='0'){echo 'New Photo';}else{echo 'Photo';}?></label>
                <input type="file" name="photo" class="photo" accept="image/png" <?php if ($machine=='0' || isset($_GET['messageError'])){echo 'required';}?>>
            </div>
            <button type="submit" name="<?php if ($machine!='0' && !isset($_GET['messageError'])){echo 'edit';}else{echo 'save';}?>" class="btn"><?php if ($machine!='0' && !isset($_GET['messageError'])){echo 'Save Changes';}else{echo 'Save Data';}?></button>
            <button type="submit" class="btn" name="cancel" id="btn_cancel" formnovalidate>Return</button>
            <?php if ($machine!='0' && !isset($_GET['messageError'])){
                    if($_SESSION['active_machine']==1){
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