<?php
   define('DB_SERVER', 'localhost');
   define('DB_USERNAME', 'root');
   define('DB_PASSWORD', 'password');
   define('DB_DATABASE', 'mileage_master');
   $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
   
   /*
   # Verify DB Connectivity if not then Echo Error
   if($db) {
        echo "success";
   }
   else {
        die("Error". mysqli_connect_error());
   }
   */
?>