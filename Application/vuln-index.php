<?php 
    # Connect to DB with Config.php
    require 'Config.php';
    include("database.class.php");	//Include MySQL database class
    include("mysql.sessions.php");	//Include PHP MySQL sessions
    $session = new Session();	//Start a new PHP MySQL session

    # Boolean Value to Display Errrors
    $showAlert = false;
    # Error Message (Empty at start)
    $errorMessage = "";

    # Ensure Creds are Passed Through Post Request
    if($_SERVER["REQUEST_METHOD"] == "POST") {

        // User & Pass are sent from a form
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Intentionally vulnerable to SQL injection
        $sql = "SELECT * FROM mileage_master.users WHERE username = '$username' AND password = '$password'";
        
        // Try-Catch Statement for Handling Query Syntax Errors
        try {
            // Launch Query Against DB
            $result = mysqli_query($db, $sql);

            // Check if any row is returned
            if ($result && mysqli_num_rows($result) > 0) {
                //set SESSION Coolie
                $_SESSION['username'] = $username;
                header("Location: Mileage.php");
                exit();
            } else {
                echo "<script language='javascript'>alert('Invalid Username or Password')</script>";
            }

        } catch (mysqli_sql_exception $e) {
            $showAlert = true;
            // Display Syntax Error
            $errorMessage = "My SQL Error: " . $e->getMessage();
            // Display Query (Intentional Unsafe Coding Practices)
            $errorMessage .= "\t||\tQuery: " . $sql;
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login - MileageMaster</title>
        <link rel="stylesheet" href="CSS\Login.css">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

        <!-- Bootstrap JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script> 
    </head>
    <style>
        .error {
            background: black;
            color: red;
            margin-bottom: 0px;
            position: absolute;
            z-index: 1;
            font-size: 25px;
            padding-left: 10px;
            padding-right: 10px;
        }
    </style>
    <body>
        <?php
            // PHP to Print the Previous MySQL Error
            if ($showAlert) {
                echo "<p class='error'>$errorMessage</p>";
            }
        ?>

        <!-- Main Login Box -->
        <div class="center login-box-main">
            <!-- Company Logo -->
            <div class="logo">
                <img src="Images/HTB Logo.jpg" alt="Company Logo">
            </div>
            <div style="text-align: center;">
                <h1 style="color: #0A5BFC;" class="mt-2"><b>Mileage Master</b></h1>
                <h6 style="font-size: 20px;">Drive Smarter, Track Better</h6>
            </div>

            <!-- Send Login POST Request -->
            <form class="p-3 mt-3" action="index.php" method="post">
                <!-- User Input Field -->
                <div class="input-fields mt-3">
                    <input type="text" name="username" id="username" placeholder="Username" required>
                </div>

                <!-- Pasword Input Field -->
                <div class="input-fields mt-3 d-flex align-items-center">
                    <input type="password" name="password" id="password" placeholder="Password" required>
                </div>

                <!-- Login Button -->
                <button class="btn mt-3" type="submit">Login</button>
            </form>

            <!-- Handling Signup -->
            <div style="text-align: center;" >
                <p>or</p>
                <H6 class="signup mt-2"><a href="Register.php">Register</a></H6>
            </div>
        </div>

        <!-- Copyright Footer -->
        <footer>
            <h6 class="copyright">Created by<span style="color: #FAC14E;font-weight: bold;letter-spacing: 1.3px;"> Ethan Tomford</span></h6>
        </footer>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>
