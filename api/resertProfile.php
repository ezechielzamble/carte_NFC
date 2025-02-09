<?php
// api/resetProfile.php
header('Content-Type: application/json');

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'carte';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

$name = "Ahida Musavu";
$description = "Resident Pastor - ACER Rennes<br>Wife of Elvis MUSAVU and Mom of 2<br>Founder of Femme de Splendeur<br>TV Host Wisdom Room EVTV<br>Director of Meira Academy";
$photo = "";
$indexBg = "#ffe6f0";
$cardBg = "#ffffff";
$contacts = json_encode([
  ['type' => 'fa-phone', 'content' => '0634116935', 'label' => 'Téléphone'],
  ['type' => 'fa-envelope', 'content' => 'ahidamusavu@gmail.com', 'label' => 'Email'],
  ['type' => 'fa-instagram', 'content' => 'https://instagram.com/ahidamusavu', 'label' => 'Instagram'],
  ['type' => 'fa-facebook', 'content' => 'https://facebook.com/AhidaSandraMusavu', 'label' => 'Facebook']
]);

$sql = "UPDATE profile 
        SET name='$name', description='$description', photo='$photo', indexBg='$indexBg', cardBg='$cardBg', contacts='$contacts'
        WHERE id=1";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => $conn->error]);
}

$conn->close();
?>
