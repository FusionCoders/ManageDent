<?php
include_once("../php/login_functions.php");
?>

<!Doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website ManageDent</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    
    <header>
        <img src="../img/logo.png" alt="logo">
        <nav class="navigation">
            <a href="../index.html">Home</a>
            <!-- <a href="../index.html#founders">About</a>
            <a href="../index.html">Contact</a> -->
            <div class="btnLogin">
                <a href="login.php" class="active">Login</a>
            </div>
        </nav>
    </header>

    <div class="wrapper-login">
        <div class="form-box login">
            <h2>Login</h2>
            <form action="../php/login_functions.php" method="POST">
                <div class="input-box">
                    <span class="icon"><ion-icon name="mail"></ion-icon></span>
                    <input type="email" name="email" required>
                    <label>Email</label>
                </div>
                <div class="input-box">
                    <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
                    <input type="password" name="password" required>
                    <label>Password</label>
                </div>
                <div class="warning">
                    <?php 
                        if (isset($_GET['message'])) {
                            // Recupere a mensagem e decodifique-a
                            $message = urldecode($_GET['message']);
                            if ($message == 'show'){
                                warning();
                            }  
                            if ($message == 'deleted'){
                                warning1();
                            }       
                        }
                    ?>
                </div>
                <button type="submit" class="btn">Login</button>
                <div class="login-register">
                    <p>Don't have an account? Talk to the administrator</p>
                </div>
            </form>
        </div>
    </div>



    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>