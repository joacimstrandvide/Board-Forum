<?php
header('Content-type: text/html');

// Open (or create) SQLite database
$db = new SQLite3('db.db');

// Create table if not exists
$db->exec("CREATE TABLE IF NOT EXISTS users (
    name TEXT PRIMARY KEY,
    id INTEGER,
    passcode TEXT,
    presentation TEXT
)");

$createName = $_POST["createEmail"];
$id = rand(10, 100);
$createPass = $_POST["createPass"];
$presentation = $_POST["presentation"];
$stmt = $db->prepare("INSERT INTO users (name, id, passcode, presentation) VALUES (:name, :id, :pass, :presentation)");
$stmt->bindValue(':name', $createName, SQLITE3_TEXT);
$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
$stmt->bindValue(':pass', $createPass, SQLITE3_TEXT);
$stmt->bindValue(':presentation', $presentation, SQLITE3_TEXT);

if ($stmt->execute()) {
    echo "<h1 style='text-align:center;'>Ny Användare har skapats vid namn " . $createName . "!</h1>";
    echo "<p style='text-align:center;'>Du skickas tillbaka till startsidan...</p>";
    echo "<meta http-equiv='refresh' content='2;url=index.html'>";
} else {
    echo "Fel meddelande: " . $db->lastErrorMsg();
}

$db->close();
