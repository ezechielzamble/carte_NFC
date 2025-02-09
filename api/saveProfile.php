<?php
// api/saveProfile.php
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    die(json_encode(['error' => 'No data provided']));
}

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'carte';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

$name = $conn->real_escape_string($data['name']);
$description = $conn->real_escape_string($data['description']);

// Vérification : si une photo a été fournie (non vide), on l'utilise ; sinon, on ne modifie pas la colonne "photo"
$photo = "";
if (isset($data['photo']) && trim($data['photo']) !== "") {
    $photo = $conn->real_escape_string($data['photo']);
}

$indexBg = $conn->real_escape_string($data['indexBg']);
$cardBg = $conn->real_escape_string($data['cardBg']);
$contacts = $conn->real_escape_string(json_encode($data['contacts']));

// Si une nouvelle photo est fournie, on l'enregistre, sinon on conserve la valeur existante
if ($photo !== "") {
    $sql = "UPDATE profile 
            SET name='$name', description='$description', photo='$photo', indexBg='$indexBg', cardBg='$cardBg', contacts='$contacts'
            WHERE id=1";
} else {
    $sql = "UPDATE profile 
            SET name='$name', description='$description', indexBg='$indexBg', cardBg='$cardBg', contacts='$contacts'
            WHERE id=1";
}

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => $conn->error]);
}

$conn->close();
?>
