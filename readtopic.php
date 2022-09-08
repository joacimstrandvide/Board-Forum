
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


        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } else {
            $email = $_POST["email"];
            $userpass = $_POST["userpass"];
            $presentation = $_POST["presentation"];
            $topicid = $_POST["topicid"];
            $header = $_POST["header"];
            $updates = $_POST["updates"];
            $originator = $_POST["originator"];

            echo "Inloggad som <a class='welcome' href=\"" . $email . "\" title=\"" . $presentation . "\" >" . $email . "</a><br /><br /><br />";
            echo "<form action='index.php' method='post'>";
            echo "<input type='hidden' name='email' value='$email'>";
            echo "<input type='hidden' name='pass' value='$userpass'>";
            echo "<input type='submit' name='submit' class='result_button' value='Tillbaka till startsidan'>";
            echo "</form>";
            echo "<br />";
            echo "<hr />";
            echo "<h1>" . $header . "</h1>";

            $sql = "SELECT * FROM posts, users WHERE topicid='$topicid' AND user=email";
            $result = $conn->query($sql);
            $nbrposts = $result->num_rows;
            echo "Det finns " . $nbrposts . " inlägg i denna tråd";
            echo "<table class=\"result\">";
            while ($row = $result->fetch_assoc()) {
                echo "<tr class=\"result\">";
                echo "<td>" . "Skrivet av";
                echo "<br>";
                echo "<br>";
                echo "<a href=\"" . $row["user"] . "\" >" . $row["user"] . "</a><br>";
                echo "<a>Inlägg</a>" . "</td>";
                echo "<td>" . $row["text"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";

            echo "Svara på denna tråd:<br>";
            echo "<form action='postconfirmation.php' method='post'>";
            echo "<input type='hidden' name='email' value='$email'>";
            echo "<input type='hidden' name='userpass' value='$userpass'>";
            echo "<input type='hidden' name='dbpass' value='$password'>";
            echo "<input type='hidden' name='presentation' value='$presentation'>";
            echo "<input type='hidden' name='topicid' value='$topicid'>";
            echo "<input type='hidden' name='updates' value='$updates'>";
            echo "<input type='hidden' name='originator' value='$originator'>";
            echo "<input type='hidden' name='nbrposts' value='$nbrposts'>";
            echo "<input type='hidden' name='header' value='$header'>";
            echo "<textarea name='content' rows='10' cols='50'></textarea><br>";
            if ($updates == 1) {
                echo "Lösenord till epostkontot:<br>";
                echo "<input type='password' name='mailpass'><br>";
            }
            echo "<input type='submit' name='submit' value='Lägg till'>";
            echo "</form>";

            $closed = mysqli_close($conn);
            if ($closed) {
                //echo "Databasuppkopplingen stängd";
            } else {
                echo "Lyckades inte stänga databasuppkopplingen";
            }
        }
    }

    ?> 
 