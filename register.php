<?php
// register.php
session_start();

// Configuration de la base de données
$host       = 'localhost';
$dbuser     = 'root';
$dbpassword = '';
$dbname     = 'digital';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbuser, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connexion à la base échouée : " . $e->getMessage());
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et nettoyage des données du formulaire
    $username         = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password         = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    // Validation des champs
    if (!$username) {
        $errors[] = "Veuillez entrer un identifiant.";
    }
    if (!$password) {
        $errors[] = "Veuillez entrer un mot de passe.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    // Vérifier que l'identifiant n'existe pas déjà
    if (empty($errors)) {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        if ($stmt->rowCount() > 0) {
            $errors[] = "Cet identifiant existe déjà.";
        }
    }

    // Insertion de l'utilisateur dans la table users
    if (empty($errors)) {
        $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            'username' => $username,
            'password' => $password // En production, utilisez password_hash($password, PASSWORD_DEFAULT)
        ]);
        if ($result) {
            $success = "Compte créé avec succès. Vous pouvez maintenant vous connecter.";
            // Optionnel : rediriger vers la page de connexion
            // header("Location: login.php");
            // exit();
        } else {
            $errors[] = "Erreur lors de la création du compte.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Création de compte</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f7f7f7;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .register-container {
      background: white;
      padding: 20px 30px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      max-width: 400px;
      width: 100%;
    }
    .register-container h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    .register-container label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }
    .register-container input {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 4px;
      box-sizing: border-box;
    }
    .register-container button {
      width: 100%;
      padding: 10px;
      background: #4CAF50;
      border: none;
      color: white;
      font-size: 16px;
      border-radius: 4px;
      cursor: pointer;
    }
    .register-container button:hover {
      background: #45a049;
    }
    .error {
      color: red;
      margin-bottom: 10px;
      text-align: center;
    }
    .success {
      color: green;
      margin-bottom: 10px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="register-container">
    <h2>Créer un compte</h2>
    <?php if (!empty($errors)): ?>
      <div class="error">
        <?php foreach($errors as $error): ?>
          <p><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="success">
        <p><?php echo htmlspecialchars($success); ?></p>
      </div>
    <?php endif; ?>
    <form action="register.php" method="post">
      <label for="username">Identifiant :</label>
      <input type="text" id="username" name="username" required>

      <label for="password">Mot de passe :</label>
      <input type="password" id="password" name="password" required>

      <label for="confirm_password">Confirmer le mot de passe :</label>
      <input type="password" id="confirm_password" name="confirm_password" required>

      <button type="submit">Créer le compte</button>
    </form>
    <p style="text-align:center; margin-top:10px;">
      Déjà un compte ? <a href="login.php">Connectez-vous</a>
    </p>
  </div>
</body>
</html>
