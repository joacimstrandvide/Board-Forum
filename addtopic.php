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
    $nbrrows = $_POST["nbrrows"];


    echo "Inloggad som <a class='welcome' href=\"profile.php?name=" . urlencode($name) . "\" title=\"" . $presentation . "\" >" . $name . "</a><br /><br /><br />";
    echo "<form action='index.php' method='post'>";
    echo "<input type='hidden' name='name' value='$name'>";
    echo "<input type='hidden' name='pass' value='$userpass'>";
    echo "<input type='submit' class='result_button' name='submit' value='Tillbaka till startsidan'>";
    echo "</form>";
    echo "<h3>Ny tråd</h3>";
    echo "<form action='addtopicconfirmation.php' method='post'>";
    echo "<input type='hidden' name='name' value='$name'>";
    echo "<input type='hidden' name='userpass' value='$userpass'>";
    echo "<input type='hidden' name='dbpass' value=''>";
    echo "<input type='hidden' name='presentation' value='$presentation'>";
    echo "<input type='hidden' name='nbrrows' value='$nbrrows'>";
    echo "Rubrik<br>";
    echo "<input type='text' name='header'><br>";
    echo "Inlägg<br>";
    echo "<textarea name='content' rows='10' cols='50'></textarea><br>";
    echo "<input type='submit' class='result_button' name='submit' value='Publicera'>";
    echo "</form>";

    $db->close();
}
