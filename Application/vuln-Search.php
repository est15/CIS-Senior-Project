<?php
    Require 'Config.php';

    include("database.class.php");    //Include MySQL database class
    include("mysql.sessions.php");    //Include PHP MySQL sessions
    $session = new Session();    //Start a new PHP MySQL session

    // Variable for Error Alerting
    $showAlert = false;
    $errorMessage = "";
    $noResult = false;
    
    // If SESSION variables are set then proceed PHP Code
    if (isset($_SESSION['username'])) {
        // PHP Code for Handling Searching for VIN
        // Declare username Variable
        $username = $_SESSION['username'];

        // Dont run Query unless vehicle parameter set
        if(isset($_POST['vehicle'])){
            // VIN variable
            $vin = $_POST['vehicle'];
            // Implement Try-catch for Error Handling
            try {
                // Example of a vulnerable SELECT query
                $query = "SELECT * FROM mileage_master.mileages WHERE VIN='$vin'";
                $result = mysqli_query($db, $query);

                // See if any VINs were returned
                if (mysqli_num_rows($result)==0) {
                    $noResult = true;
                } else {
                    // Fetch and display the result
                    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
                }   
            }
            catch (mysqli_sql_exception $e) {
                $showAlert = true;
                // Display Syntax Error
                $errorMessage = "My SQL Error: " . $e->getMessage();
                // Display Query (Intentional Unsafe Coding Practices)
                $errorMessage .= "\t||\tQuery: " . $query;
            }
        }
    }
    else {
        header("Location: /");
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Search VIN</title>
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
    <body style="background: rgb(235, 231, 231);">
        <?php
            // MySQL Error Output
            if($showAlert) {
                echo "<p class='error'>$errorMessage</p>";
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
                <a href="Mileage.php" style="margin-top: 5px;" class="home-link" alt="Home Link">HOME</a>
            </nav>    
        </div>
        
        <!-- Main Page Content -->
        <div class="main" style="text-align: center;">
            <h1 style="padding-top: 2%; font-weight: bold; font-size: 75px; -webkit-text-stroke: 1px black;"><span style="color: #FAC14E;">-</span> <span style="color: #0A5BFC;">MileageMaster.com</span> <span style="color: #FAC14E;">-</span></h1>
           
            
            <!-- Box Search VIN Content -->
            <div>
                <!-- Form for Adding VIN -->
                <form class="p-3 mt-3" action="Search.php" method="post">
                    <!-- Direct User to Fillout Accordingly -->
                    <h6 style="margin-top: 25px; color: #000000; font-size: 35px;">Search for VIN:</h6>

                    <div style="display: flex; flex-wrap: wrap;" class="push">
                        <!-- VIN Input Field -->
                         <div class="mt-3">
                            <input style="width: 750px; font-size: 25px;" type="text" name="vehicle" id="vehicle" placeholder="Vehicle ID #" required>
                        </div>
                    </div>
                    
                    <!-- Add VIN & Mileage Button -->
                    <button class="btn mt-4" type="submit">Search VIN</button>
                </form>
            </div>
            <?php
                // Display the array in a tabular format with inline styling
                if (!empty($rows)) {
                    echo "<table style='width: 60%; border-collapse: collapse; margin: 0 auto;'>";
                    // Print table header
                    echo "<tr style='background-color: #f2f2f2;'>";
                    foreach ($rows[0] as $key => $value) {
                        echo "<th style='border: 1px solid black; padding: 8px;'>$key</th>";
                    }
                    echo "</tr>";
                    // Print table rows
                    foreach ($rows as $row) {
                        echo "<tr>";
                        foreach ($row as $value) {
                            echo "<td style='border: 1px solid black; padding: 8px;'>$value</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                } elseif($noResult) {
                    echo "<p>No results found.</p>";
                }
            ?>
        </div>
    </body>
</html>
