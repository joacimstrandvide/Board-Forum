 <?php 

 header('Content-type: text/html');
 
 $html = file_get_contents("index.html");
 $html_pieces = explode("<!-- ==xxx== -->", $html);
 echo $html_pieces[0]; 

 
 if (count($_POST) > 0) {
     
     $servername = "localhost"; 
     $username = "root";
     $password = "";
     $dbname = "users";
     
     // Create connection
     $conn = new mysqli($servername, $username, $password, $dbname);
    
     // Check connection
     if ($conn->connect_error) {
         die("Connection failed: " . $conn->connect_error);
     } 
     else {
        // uppkopplad till databasen
        if (count($_POST) > 0) {
            

            // läs från databasen
            
            $email = htmlspecialchars($_POST["email"], ENT_QUOTES);
            $pass = htmlspecialchars($_POST["pass"], ENT_QUOTES);
            $sql = "SELECT * FROM users WHERE email='$email' AND passcode='$pass'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                // läs data för varje rad
                $presentation = "";
                while($row = $result->fetch_assoc()) { 
                    
                    $presentation = $row["presentation"];
                    echo "Inloggad som <a class='welcome' href=\"" . $email . "\" title=\"" . $presentation . "\" >" . $email . "</a><br /><br /><br /><hr>";
                    
                }
                
               
                $sql = "SELECT topics.id AS id, header, originator, presentation, updates FROM topics, users WHERE originator=email";
                $result = $conn->query($sql);
                $nbrrows = $result->num_rows;
                echo "<form action='addtopic.php' method='post'>";
                echo "<input type='hidden' name='email' value='$email'>";
                echo "<input type='hidden' name='userpass' value='$pass'>";
                echo "<input type='hidden' name='dbpass' value='$password'>";
                echo "<input type='hidden' name='presentation' value='$presentation'>";
                echo "<input type='hidden' name='nbrrows' value='$nbrrows'>";
                echo "<input class='result_button' type='submit' name='submit' value='Skapa ny tråd'>";
                echo "</form>";
                if ($nbrrows > 0) {
                    echo "<p>Det finns ";
                    echo $nbrrows;
                    echo " tråd";
                    if ($nbrrows > 1) {
                        echo "ar";
                    }
                    echo ":</p><table class=\"result\"><tr class=\"result\">
                        <th></th>
                        <th>Nr</th>
                        <th>Titel</th> 
                        <th>Skapad av</th>
                        <th>Tid</th>
                        </tr>";
                    while($row = $result->fetch_assoc()) {
                        echo "<tr class=\"result\"><td>";
                        $topicid = $row["id"];
                        $header = $row["header"];
                        $updates = $row["updates"];
                        $originator = $row["originator"];
                        echo "<form action='readtopic.php' method='post'>";
                        echo "<input type='hidden' name='email' value='$email'>";
                        echo "<input type='hidden' name='userpass' value='$pass'>";
                        echo "<input type='hidden' name='presentation' value='$presentation'>";
                        echo "<input type='hidden' name='header' value='$header'>";
                        echo "<input type='hidden' name='topicid' value='$topicid'>";
                        echo "<input type='hidden' name='updates' value='$updates'>";
                        echo "<input type='hidden' name='originator' value='$originator'>";
                        echo "<input class='result_button' type='submit' name='submit' value='Läs'>";
                        echo "</form></td><td>";
                        echo $topicid+1;
                        echo "</td><td>";
                        echo $header;
                        echo "</td><td>";
                        echo "<a href=\"" . $originator . "\" title=\"" . $row["presentation"] . "\" >" . $originator . "</a><br>";
                        echo "</td><td>";
                        $sql = "SELECT MAX(time) AS maxtime FROM posts WHERE topicid='$topicid'";
                        $result2 = $conn->query($sql);
                        while($row = $result2->fetch_assoc()) { 
                            echo $row["maxtime"];
                        }
                        
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
                else {
                    echo "<p>Det finns ännu inga trådar.</p>";
                }
            } else {
                echo "<script>window.confirm('Login misslyckades! Var vänlig försök igen.');</script>";
            }
            
            
        }
        
        $closed = mysqli_close($conn);
        if ($closed) {
            //echo "Databasuppkopplingen stängd";
        }
        else {
            echo "Lyckades inte stänga databasuppkopplingen";
        }
     }


 }


 ?> 