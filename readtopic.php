<?php
header('Content-type: text/html');

$html = file_get_contents("index.html");
$html_pieces = explode("<!-- ==xxx== -->", $html);
echo $html_pieces[0];

if (count($_POST) > 0) {

    $db = new SQLite3('db.db');


    $name = $_POST["name"];
    $userpass = $_POST["userpass"];
    $presentation = $_POST["presentation"];
    $topicid = $_POST["topicid"];
    $header = $_POST["header"];
    $updates = $_POST["updates"];
    $originator = $_POST["originator"];

    echo "Inloggad som <a class='username' href=\"profile.php?name=" . urlencode($name) . "\" title=\"" . $presentation . "\" >" . $name . "</a><br /><br /><br />";
    echo "<form action='index.php' method='post'>";
    echo "<input type='hidden' name='name' value='$name'>";
    echo "<input type='hidden' name='pass' value='$userpass'>";
    echo "<input type='submit' name='submit' class='thread-btn' value='Tillbaka till startsidan'>";
    echo "</form>";
    echo "<br />";
    echo "<hr />";
    echo "<h1>" . $header . "</h1>";

    $stmt = $db->prepare("SELECT posts.*, users.presentation FROM posts JOIN users ON posts.user = users.name WHERE topicid = :topicid");
    $stmt->bindValue(':topicid', $topicid, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $nbrposts = 0;
    $rows = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $rows[] = $row;
        $nbrposts++;
    }
    echo "Det finns " . $nbrposts . " inlägg i denna tråd";
    echo "<table class=\"result\">";
    foreach ($rows as $row) {
        echo "<tr class=\"result\">";
        echo "<td>" . "Skrivet av";
        echo "<br>";
        echo "<br>";
        echo "<a href=\"" . $row["user"] . "\" >" . $row["user"] . "</a><br>";
        echo "<td>" . $row["text"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "Svara på denna tråd:<br>";
    echo "<form action='postconfirmation.php' method='post'>";
    echo "<input type='hidden' name='name' value='$name'>";
    echo "<input type='hidden' name='userpass' value='$userpass'>";
    echo "<input type='hidden' name='dbpass' value=''>";
    echo "<input type='hidden' name='presentation' value='$presentation'>";
    echo "<input type='hidden' name='topicid' value='$topicid'>";
    echo "<input type='hidden' name='updates' value='$updates'>";
    echo "<input type='hidden' name='originator' value='$originator'>";
    echo "<input type='hidden' name='nbrposts' value='$nbrposts'>";
    echo "<input type='hidden' name='header' value='$header'>";
    echo "<textarea name='content' rows='10' cols='50'></textarea><br>";
    echo "<input type='submit' name='submit' value='Lägg till'>";
    echo "</form>";

    $db->close();
}
