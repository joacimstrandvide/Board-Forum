<?php

header('Content-type: text/html');

$html = file_get_contents("index.html");
$html_pieces = explode("<!-- ==xxx== -->", $html);
echo $html_pieces[0];


if (count($_POST) > 0) {

    // Open SQLite database
    $db = new SQLite3('db.db');

    $name = htmlspecialchars($_POST["name"], ENT_QUOTES);
    $pass = htmlspecialchars($_POST["pass"], ENT_QUOTES);
    $stmt = $db->prepare("SELECT * FROM users WHERE name = :name AND passcode = :pass");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':pass', $pass, SQLITE3_TEXT);
    $result = $stmt->execute();

    if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $presentation = $row["presentation"];
        echo "Inloggad som <a class='welcome' href=\"profile.php?name=" . urlencode($name) . "\" title=\"" . $presentation . "\" >" . $name . "</a><br />";

        // List topics
        $topics = $db->query("SELECT topics.id AS id, header, originator, presentation, updates FROM topics JOIN users ON originator=users.name");
        $nbrrows = 0;
        $topicRows = [];
        while ($row = $topics->fetchArray(SQLITE3_ASSOC)) {
            $topicRows[] = $row;
            $nbrrows++;
        }
        echo "<form action='addtopic.php' method='post'>";
        echo "<input type='hidden' name='name' value='$name'>";
        echo "<input type='hidden' name='userpass' value='$pass'>";
        echo "<input type='hidden' name='dbpass' value=''>";
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
                <th>ID</th>
                <th>Titel</th>
                <th>Skapad av</th>
                <th>Tid</th>
                </tr>";
            foreach ($topicRows as $row) {
                echo "<tr class=\"result\"><td>";
                $topicid = $row["id"];
                $header = $row["header"];
                $updates = $row["updates"];
                $originator = $row["originator"];
                echo "<form action='readtopic.php' method='post'>";
                echo "<input type='hidden' name='name' value='$name'>";
                echo "<input type='hidden' name='userpass' value='$pass'>";
                echo "<input type='hidden' name='presentation' value='$presentation'>";
                echo "<input type='hidden' name='header' value='$header'>";
                echo "<input type='hidden' name='topicid' value='$topicid'>";
                echo "<input type='hidden' name='updates' value='$updates'>";
                echo "<input type='hidden' name='originator' value='$originator'>";
                echo "<input class='result_button' type='submit' name='submit' value='Läs'>";
                echo "</form></td><td>";
                echo $topicid + 1;
                echo "</td><td>";
                echo $header;
                echo "</td><td>";
                echo "<a href=\"" . $originator . "\" title=\"" . $row["presentation"] . "\" >" . $originator . "</a><br>";
                echo "</td><td>";
                $stmt2 = $db->prepare("SELECT MAX(time) AS maxtime FROM posts WHERE topicid = :topicid");
                $stmt2->bindValue(':topicid', $topicid, SQLITE3_INTEGER);
                $result2 = $stmt2->execute();
                $row2 = $result2->fetchArray(SQLITE3_ASSOC);
                echo $row2["maxtime"];
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Det finns ännu inga trådar.</p>";
        }
    } else {
        echo "<script>window.confirm('Login misslyckades! Var vänlig försök igen.');</script>";
    }

    $db->close();
}
?>
