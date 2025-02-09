<?php
// api/getProfile.php
header('Content-Type: application/json');

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'carte';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

$sql = "SELECT * FROM profile WHERE id = 1";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Décodage du champ contacts (stocké sous forme de chaîne JSON)
    $row['contacts'] = json_decode($row['contacts'], true);
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'Profile not found']);
}
$conn->close();
?>
