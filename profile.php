<?php
header('Content-type: text/html');

$html = file_get_contents("index.html");
$html_pieces = explode("<!-- ==xxx== -->", $html);
echo $html_pieces[0];

if (isset($_GET['name'])) {
    $db = new SQLite3('db.db');
    $name = $_GET['name'];

    $stmt = $db->prepare("SELECT * FROM users WHERE name = :name");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);

    if ($user) {
        echo "<h1>Profil</h1>";
        echo "<p><strong>Namn:</strong> " . htmlspecialchars($user['name']) . "</p>";
        echo "<p><strong>Beskrivning:</strong> " . nl2br(htmlspecialchars($user['presentation'])) . "</p>";
        echo "<form action='index.php' method='post'>";
        echo "<input type='hidden' name='name' value='" . htmlspecialchars($user['name']) . "'>";
        echo "<input type='hidden' name='pass' value='" . htmlspecialchars($user['passcode']) . "'>";
        echo "<input type='submit' class='result_button' name='submit' value='Tillbaka till startsidan'>";
        echo "</form>";
    } else {
        echo "<p>Användare hittades inte.</p>";
    }
    $db->close();
} else {
    echo "<p>Ingen användare angiven.</p>";
}
?>
