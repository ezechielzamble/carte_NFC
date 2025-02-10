<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(["error" => "Accès non autorisé."]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    echo json_encode(["error" => "Entrée invalide."]);
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

$user_id    = $_SESSION['user_id'];
$name       = isset($data['name']) ? $data['name'] : '';
$description= isset($data['description']) ? $data['description'] : '';
$photo      = isset($data['photo']) ? $data['photo'] : '';
$indexBg    = isset($data['indexBg']) ? $data['indexBg'] : '#ffe6f0';
$cardBg     = isset($data['cardBg']) ? $data['cardBg'] : '#ffffff';
$contacts   = isset($data['contacts']) ? json_encode($data['contacts']) : json_encode([]);

// Vérifier si un profil existe déjà pour cet utilisateur
$sql = "SELECT id FROM profiles WHERE user_id = :user_id LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

if ($profile) {
    // Mise à jour du profil existant
    $sql = "UPDATE profiles 
            SET name = :name, description = :description, photo = :photo, indexBg = :indexBg, cardBg = :cardBg, contacts = :contacts 
            WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        'name'       => $name,
        'description'=> $description,
        'photo'      => $photo,
        'indexBg'    => $indexBg,
        'cardBg'     => $cardBg,
        'contacts'   => $contacts,
        'user_id'    => $user_id
    ]);
} else {
    // Insertion d'un nouveau profil
    $sql = "INSERT INTO profiles (user_id, name, description, photo, indexBg, cardBg, contacts) 
            VALUES (:user_id, :name, :description, :photo, :indexBg, :cardBg, :contacts)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        'user_id'    => $user_id,
        'name'       => $name,
        'description'=> $description,
        'photo'      => $photo,
        'indexBg'    => $indexBg,
        'cardBg'     => $cardBg,
        'contacts'   => $contacts
    ]);
}

if ($result) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Erreur lors de l'enregistrement dans la base de données."]);
}
?>
