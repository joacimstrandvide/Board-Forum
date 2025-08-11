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
    $text = htmlspecialchars($_POST["content"], ENT_QUOTES);
    $text = str_replace('<', '&lt;', $text);
    $text = str_replace('>', '&gt;', $text);
    $text = str_replace('å', '&aring;', $text);
    $text = str_replace('ä', '&auml;', $text);
    $text = str_replace('ö', '&ouml;', $text);
    $text = str_replace('Å', '&Aring;', $text);
    $text = str_replace('Ä', '&Auml;', $text);
    $text = str_replace('Ö', '&Ouml;', $text);
    $updates = $_POST["updates"];
    $nbrposts = $_POST["nbrposts"];

    echo "Inloggad som <a class='username' href=\"profile.php?name=" . urlencode($name) . "\" title=\"" . $presentation . "\" >" . $name . "</a><br>";
    echo "<form action='index.php' method='post'>";
    echo "<input type='hidden' name='name' value='$name'>";
    echo "<input type='hidden' name='pass' value='$userpass'>";
    echo "<br />";
    echo "<br />";
    echo "<input type='submit' class='thread-btn' name='submit' value='Tillbaka till startsidan'>";
    echo "</form>";

    // Insert post
    $stmt = $db->prepare("INSERT INTO posts (topicid, time, user, text) VALUES (:topicid, datetime('now'), :user, :text)");
    $stmt->bindValue(':topicid', $topicid, SQLITE3_INTEGER);
    $stmt->bindValue(':user', $name, SQLITE3_TEXT);
    $stmt->bindValue(':text', $text, SQLITE3_TEXT);
    $stmt->execute();
    echo "Inlägget har sparats.<br>";

    $db->close();
}
