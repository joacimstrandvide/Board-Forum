<?php
header('Content-type: text/html');

     $servername = "localhost"; 
     $username = "root";
     $password = "";
     $dbname = "users";
     
     $conn = new mysqli($servername, $username, $password, $dbname);
    
     if ($conn->connect_error) {
         die("Connection failed: " . $conn->connect_error);
     } 

     $createEmail = $_POST["createEmail"];
     $id = (rand(10,100));
     $createPass = $_POST["createPass"];
     $presentation =  $_POST["presentation"];
     $sql = "INSERT INTO `users`(`email`, `id`, `passcode`, `presentation`) VALUES ('$createEmail', '$id', '$createPass' ,'$presentation')";

     if ($conn->query($sql) === TRUE) {
        echo "<h1 style='text-align:center;'>Ny Användare har skapats vid namn " . $createEmail . "!</h1>";
      } else {
        echo "Fel meddelande: " . $sql . "<br>" . $conn->error;
      }
      
      $conn->close();

?>
 