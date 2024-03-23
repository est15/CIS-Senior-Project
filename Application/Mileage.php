<?php
    Require 'Config.php';

    include("database.class.php");	//Include MySQL database class
    include("mysql.sessions.php");	//Include PHP MySQL sessions
    $session = new Session();	//Start a new PHP MySQL session

    // Variable for Error Alerting
    $showAlert = false;
    $updateError = false;
    
    // If SESSION variables are set then proceed to Mileage.php
    if (isset($_SESSION['username'])) {
        // PHP Code for Handling ADDING CAR
        // Declare Variables
        $username = $_SESSION['username'];
        $vin = $_POST['vehicle'];
        $mileage = $_POST['mileage'];

        // Implement Try-catch for Error Hanlding
        try {
            // Add Mileage
            if(isset($_POST['add_data'])) {
                // Statement to Configure
                $add_stmt = mysqli_prepare($db,"INSERT INTO mileage_master.mileages (`username`, `VIN`, `mileage`) VALUES (?, ?, ?)");

                // Bind the Parameters
                mysqli_stmt_bind_param($add_stmt, "sss", $username, $vin, $mileage);

                // Execute Prepared Statement
                mysqli_stmt_execute($add_stmt);
            // Update Mileage
            } elseif(isset($_POST['update_data'])) {
                // Statement to Configure
                $update_stmt = mysqli_prepare($db,"UPDATE mileage_master.mileages SET mileage = ? WHERE username = ? AND VIN = ?");

                // Bind the Parameters
                mysqli_stmt_bind_param($update_stmt, "sss", $mileage, $username, $vin);

                // Execute Prepared Statement
                mysqli_stmt_execute($update_stmt);
            // Remove Mileage
            } elseif(isset($_POST['remove_data'])) {
                // Statement to Configure
                $remove_stmt = mysqli_prepare($db,"DELETE FROM mileage_master.mileages WHERE username = ? AND VIN = ?");

                // Bind the Parameters
                mysqli_stmt_bind_param($remove_stmt, "ss", $username, $vin);

                // Execute Prepared Statement
                mysqli_stmt_execute($remove_stmt);
            }
        }
        // Display Generic Error if Backend Code Fails
        catch (mysqli_sql_exception $e) {
            $showAlert = true;
            // Display Generic Error
            $errorMessage = "Error Encountered";
        }
    }    
    else {
        header("Location: /");
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Dashboard</title>
        <link rel="stylesheet" href="CSS\Index.css">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        
    </head>
    <style type="text/css">
        .logout, .home-link {
            font-weight: bold;
            font-size: 25px;
            letter-spacing: 1.3px;
            height: 100px;
            width: 100%;
            display: inline-block; /* Keep it as inline-block */
            overflow: hidden;
            background-color: #0A5BFC;
            color: white;
            text-decoration: none; /* Added to remove underline from links */
            text-align: center; /* Added to center text horizontally */
            line-height: 100px; /* Added to center text vertically */
        }

        .logout:hover, .home-link:hover {
            background: #4380f9;
            color: white;
        }

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

        table, tr, td {
            width: 30%;
            border: 1px solid black;
            background: white;
            margin-left: 35%;
            font-size: 22px;
            overflow: scroll;
        }
    </style>
    <!-- JS to Ensure VIN & Mileage meet criteria -->
    <script type="text/javascript">
        function validateInput(){
            let vin = document.getElementById("vehicle").value;
            let mile = document.getElementById("mileage").value;

            //Ensure VIN Length is Greater than 11
            if (vin.length < 11) {
                alert("VIN Number must be greater than 11 characters.");
                return false;
            }
            // Ensure VIN doesnt contain whitespaces
            else if (vin.indexOf(" ") > 0) {
                alert("VIN Number cannot contain whitespaces");
                return false;
            }
            // Ensure that Mileage is an Integer:
            if (!Number.isInteger(Number(mile))) {
                alert("Mileage must be an interger (Ex: 4523). No Decimals allowed.");
                return false;
            }
            
            // Ensure Mileage is positive
            else if (Number(mile) < 0) {
                alert("Mileage must be a positive number.");
                return false;
            }

            // If both VIN & Mileage pass then proceed                
            return true;
        }
    </script>
    <body style="background: rgb(235, 231, 231);">
        <?php
            // MySQL Error Output
            if($showAlert) {
                echo "<p class='error'>$errorMessage</p>";
            }
            
            // MySQL Update Error Output
            if($updateError) {
                echo "<p class='error'>The car's VIN has already been entered. Use the update button to update " . $vin . "'s mileage.</p>";
            }
        ?>

        <!-- Navigation Bar -->
        <div>
            <nav class="sidenav">
                <!-- Company Logo -->
                <div class="logo">
                    <img src="Images/HTB Logo.jpg" alt="Company Logo">
                </div>
    
                <!-- Company Name & Slogan -->
                <div style="text-align: center;">
                    <h1 style="color: #0A5BFC; -webkit-text-stroke: 0.5px black;" class="mt-2"><b>Mileage Master</b></h1>
                    <h6 class="motto">Drive Smarter, Track Better</h6>
                </div>

                <!-- Button for Logout --> 
                <form action="logout.php" method="post">
                    <button class="logout mt-4" type="submit">
                        <img width="45" height="45" id="logout-icon" style="margin-left: 5px; margin-right: 5px;" src="Images/sign-out-alt.png" alt="logout-icon"/>
                        Logout
                    </button>
                </form>

               <!-- Link for Home Page -->
               <a href="Search.php" style="margin-top: 5px;" class="home-link" alt="Home Link">SEARCH VIN</a>
            </nav>    
        </div>
        
        <!-- Main Page Content -->
        <div class="main" style="text-align: center;">
            <h1 style="padding-top: 2%; font-weight: bold; font-size: 75px; -webkit-text-stroke: 1px black;"><span style="color: #FAC14E;">-</span> <span style="color: #0A5BFC;">MileageMaster.com</span> <span style="color: #FAC14E;">-</span></h1>
           
            
            <!-- Box Holding Mile Content -->
            <div>
                <!-- Form for Adding VIN -->
                <form class="p-3 mt-3" onsubmit="return validateInput()" action="Mileage.php" method="post">
                    <!-- Direct User to Fillout Accordingly -->
                    <h6 style="margin-top: 25px; color: #000000; font-size: 25px;">Add Your Car:</h6>

                    <div style="display: flex; flex-wrap: wrap;" class="push">
                        <!-- VIN Input Field -->
                         <div class="mt-3" style="padding-right: 25px;">
                            <input type="text" name="vehicle" id="vehicle" placeholder="Vehicle ID #" required>
                        </div>

                        <!-- Mileage Input Field -->
                        <div class="mt-3">
                            <input type="text" name="mileage" id="mileage" placeholder="Mileage (Ex: 10000)" required>
                        </div>
                    </div>
                    
                    <!-- Add VIN & Mileage Button -->
                    <button class="btn mt-4" name="add_data" type="submit">Add Car</button>

                    <!-- Update VIN & Mileage -->
                    <button class="btn mt-4" name="update_data" type="submit">Update Car</button>

                     <!-- Remove VIN & Mileage -->
                     <button class="btn mt-4" name="remove_data" type="submit">Remove Car</button>
                </form>

                <?php
                    // Query to get all VINs associated with username
                    $result = mysqli_query($db,"SELECT * FROM mileages WHERE username='$username'");

                    echo "<table style='margin-top: 35px;'>";
                    echo "<tr><th>VIN</th>";
                    echo "<th>Mileage</th></tr>";

                    // While Loop to iterate through all returned rows
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr style='text-align: center;'>";
                        // VIN
                        echo "<td>" . $row['VIN'] . "</td>";
                        // Mileage
                        echo "<td>" . $row['mileage'] . "</td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                ?>
            </div>
        </div>
    </body>
</html>