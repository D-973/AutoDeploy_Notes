<?php
   require_once 'config.php';

   // Create connection
   $conn = new mysqli(HOST_DB, USER_DB, PASS_DB, NAME_DB);

   // Check connection
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }
   echo "Connected successfully to the database.";
   $conn->close();
   ?>
   