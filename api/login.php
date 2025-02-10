<?php
session_start();

// Configuration de la base de données
$host     = 'localhost';
$dbuser   = 'root';
$dbpassword = '';
$dbname   = 'digital';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbuser, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connexion BD échouée : " . $e->getMessage());
}

// Récupération des données du formulaire
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (!$username || !$password) {
    header("Location: ../login.php?error=1");
    exit();
}

// Recherche de l'utilisateur dans la table users
$sql = "SELECT * FROM users WHERE username = :username LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // Pour cet exemple, on compare en clair (mais utilisez password_verify() si les mots de passe sont hachés)
    if ($user['password'] === $password) {
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $user['id'];
        header("Location: ../page2.php");
        exit();
    } else {
        header("Location: ../login.php?error=1");
        exit();
    }
} else {
    header("Location: ../login.php?error=1");
    exit();
}
?>
