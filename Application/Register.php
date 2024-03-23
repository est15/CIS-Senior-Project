<?php
    # Boolean Values to Handle Errors During Registration
    $showAlert = false;
    $exists = false;

    # Ensure Creds are Passed Through Post Request
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        # Connect to Database
        require 'Config.php';   

        // Full_name, Email, Username & Password are sent from a form
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['conf_password'];
        
        // See if Username Already Exists
        $user_stmt = mysqli_prepare($db, "SELECT * FROM mileage_master.users WHERE username = ?");
        
        // Bind Parameters to Prepared Statement
        mysqli_stmt_bind_param($user_stmt, "s", $username);

        // Execute Prepared Statement
        mysqli_stmt_execute($user_stmt);

        // Return Results
        $user_result = mysqli_stmt_get_result($user_stmt);
        // Number of Rows
        $num = mysqli_num_rows($user_result);

        // Actions based on if username does or does not exist
        if($num == 0) {
            // Add the Values to users Table
            $add_stmt = mysqli_prepare($db, "INSERT INTO mileage_master.users (`fullname`,`email`,`username`,`password`,`date`) VALUES (?, ?, ?, ?, current_timestamp())");
            
            // Bind Parameters to Add
            mysqli_stmt_bind_param($add_stmt, 'ssss', $fullname, $email, $username, $password);

            // Launch the Query Against the Database
            mysqli_stmt_execute($add_stmt);


            if (mysqli_stmt_affected_rows($add_stmt) > 0) {
                $showAlert = true;
            }
        }
        
        // If Username Does Exist Output Error
        if($num > 0){
            $exists = true;
        }   
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Registration - MileageMaster</title>
        <link rel="stylesheet" href="CSS\Register.css">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    </head>
    <body>
        <!-- PHP Error Output Handling -->
        <?php 
            if($showAlert) { 
                echo ' <script>alert("Success! You Can Now Login.");window.location.href = "index.php";</script> ';
                
            } 

            if($exists) { 
                echo '<script>alert("ERROR! Username Already Exists")</script>';  
            } 
        ?> 

        <!-- Main Registration Box -->
        <div class="center register-box-main">
            <!-- Company Logo -->
            <div class="logo">
                <img src="Images/HTB Logo.jpg" alt="Company Logo">
            </div>
            <!-- Registration Text -->
            <div style="text-align: center;">
                <h1 style="color: #0A5BFC;" class="mt-2"><b>Welcome!</b></h1>
                <h6 style="font-size: 20px;">Enter the Following Information:</h6>
            </div>

            <!-- Input Fields (name, email, username, password)-->
            <form class="p-3 mt-3" action="Register.php" method="post">
                <div class="field-member mt-3">
                    <label for="name">Full Name:</label>
                    <input type="text" name="fullname" id="fullname" placeholder="John Doe" required/>    
                </div>
                
                <div class="field-member mt-3">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" placeholder="jdoe@example.com" required/>
                </div>
                
                <div class="field-member mt-3">
                   <label for="username">Username:</label>
                    <input type="text" name="username" id="username" placeholder="uesr1234" required/>
                </div>

                <div class="field-member mt-3">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="************" required/>
                </div>

                <div class="field-member mt-3">
                    <label for="confirm_password">Confirm:</label>
                    <input type="password" name="conf_password" id="conf_password" placeholder="************" required/>
                </div>

                <!-- Validate Password JavaScript -->
                <script>
                    var password = document.getElementById("password")
                    var confirm_password = document.getElementById("confirm_password");

                    function validatePassword(){
                    if(password.value != confirm_password.value) {
                        confirm_password.setCustomValidity("Passwords Don't Match");
                    } else {
                        confirm_password.setCustomValidity('');
                    }
                    }

                    password.onchange = validatePassword;
                    confirm_password.onkeyup = validatePassword;
                </script>

                <!-- Login Button -->
                <button class="btn mt-3" type="submit">Register</button>
            </form>

            <!-- Handling Signup -->
            <div style="text-align: center;">
                <H6 class="cancel mt-2"><a href="index.php">Cancel</a></H6>
            </div>
        </div>

        <!-- Copyright Footer -->
        <footer>
            <h6 class="copyright">Created by 
                <span style="color: #FAC14E;font-weight: bold;letter-spacing: 1.3px;">Ethan Tomford</span>
            </h6>
        </footer>

         <!-- Bootstrap JS -->
         <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>