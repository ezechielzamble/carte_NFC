<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(["error" => "Accès non autorisé."]);
    exit();
}

$host     = 'localhost';
$dbuser   = 'root';
$dbpassword = '';
$dbname   = 'digital';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbuser, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(["error" => "Connexion BD échouée : " . $e->getMessage()]);
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM profiles WHERE user_id = :user_id LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

if ($profile) {
    // Décodage des contacts (stockés en JSON)
    $contacts = json_decode($profile['contacts'], true);
    $profile['contacts'] = $contacts;
    echo json_encode($profile);
} else {
    // Aucun profil trouvé : renvoie des valeurs par défaut
    $default = [
        "name" => "",
        "description" => "",
        "photo" => "",
        "indexBg" => "#ffe6f0",
        "cardBg" => "#ffffff",
        "contacts" => []
    ];
    echo json_encode($default);
}
?>
