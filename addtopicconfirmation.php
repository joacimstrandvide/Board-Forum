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
    $nbrrows = intval($_POST["nbrrows"]);
    echo "Inloggad som <a class='username' href=\"profile.php?name=" . urlencode($name) . "\" title=\"" . $presentation . "\" >" . $name . "</a><br />";
    echo "<form action='index.php' method='post'>";
    echo "<input type='hidden' name='name' value='$name'>";
    echo "<input type='hidden' name='pass' value='$userpass'>";
    echo "<input type='submit' class='thread-btn' name='submit' value='Tillbaka till startsidan'>";
    echo "</form>";

    $header = htmlspecialchars($_POST["header"], ENT_QUOTES);
    $header = str_replace('<', '&lt;', $header);
    $header = str_replace('>', '&gt;', $header);
    $header = str_replace('å', '&aring;', $header);
    $header = str_replace('ä', '&auml;', $header);
    $header = str_replace('ö', '&ouml;', $header);
    $header = str_replace('Å', '&Aring;', $header);
    $header = str_replace('Ä', '&Auml;', $header);
    $header = str_replace('Ö', '&Ouml;', $header);
    $text = htmlspecialchars($_POST["content"], ENT_QUOTES);
    $text = str_replace('<', '&lt;', $text);
    $text = str_replace('>', '&gt;', $text);
    $text = str_replace('å', '&aring;', $text);
    $text = str_replace('ä', '&auml;', $text);
    $text = str_replace('ö', '&ouml;', $text);
    $text = str_replace('Å', '&Aring;', $text);
    $text = str_replace('Ä', '&Auml;', $text);
    $text = str_replace('Ö', '&Ouml;', $text);

    // Create tables if not exist
    $db->exec("CREATE TABLE IF NOT EXISTS topics (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        header TEXT,
        originator TEXT,
        updates INTEGER
    )");
    $db->exec("CREATE TABLE IF NOT EXISTS posts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        topicid INTEGER,
        time TEXT,
        user TEXT,
        text TEXT
    )");

    // Insert topic
    $subscribe = 0;
    $stmt = $db->prepare("INSERT INTO topics (header, originator, updates) VALUES (:header, :originator, :updates)");
    $stmt->bindValue(':header', $header, SQLITE3_TEXT);
    $stmt->bindValue(':originator', $name, SQLITE3_TEXT);
    $stmt->bindValue(':updates', $subscribe, SQLITE3_INTEGER);
    $stmt->execute();

    // Get last inserted topic id
    $topicid = $db->lastInsertRowID();

    // Insert post
    $stmt2 = $db->prepare("INSERT INTO posts (topicid, time, user, text) VALUES (:topicid, datetime('now'), :user, :text)");
    $stmt2->bindValue(':topicid', $topicid, SQLITE3_INTEGER);
    $stmt2->bindValue(':user', $name, SQLITE3_TEXT);
    $stmt2->bindValue(':text', $text, SQLITE3_TEXT);
    $stmt2->execute();

    echo "Inlägget har sparats.";

    $db->close();
}
