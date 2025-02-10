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

// Valeurs par défaut
$default_name        = "";
$default_description = "";
$default_photo       = "";
$default_indexBg     = "#ffe6f0";
$default_cardBg      = "#ffffff";
$default_contacts    = json_encode([]);

// Mise à jour du profil avec les valeurs par défaut
$sql = "UPDATE profiles 
        SET name = :name, description = :description, photo = :photo, indexBg = :indexBg, cardBg = :cardBg, contacts = :contacts 
        WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$result = $stmt->execute([
    'name'       => $default_name,
    'description'=> $default_description,
    'photo'      => $default_photo,
    'indexBg'    => $default_indexBg,
    'cardBg'     => $default_cardBg,
    'contacts'   => $default_contacts,
    'user_id'    => $user_id
]);

if ($result) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Échec de la réinitialisation du profil."]);
}
?>
