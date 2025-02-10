<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f7f7f7;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .login-container {
      background: white;
      padding: 20px 30px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      max-width: 400px;
      width: 100%;
    }
    .login-container h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    .login-container label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }
    .login-container input {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 4px;
      box-sizing: border-box;
    }
    .login-container button {
      width: 100%;
      padding: 10px;
      background: #e91e63;
      border: none;
      color: white;
      font-size: 16px;
      border-radius: 4px;
      cursor: pointer;
    }
    .login-container button:hover {
      background: #d81b60;
    }
    .error {
      color: red;
      text-align: center;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Connexion</h2>
    <!-- Affichage d'une erreur en cas d'identifiants incorrects -->
    <?php if (isset($_GET['error'])): ?>
      <p class="error">Identifiant ou mot de passe incorrect.</p>
    <?php endif; ?>
    <!-- Le formulaire pointe vers le script de connexion -->
    <form action="api/login.php" method="post">
      <label for="username">Identifiant :</label>
      <input type="text" name="username" id="username" required>
      
      <label for="password">Mot de passe :</label>
      <input type="password" name="password" id="password" required>
      
      <button type="submit">Se connecter</button>
    </form>
    <p style="text-align:center; margin-top:10px;">
      Pas encore de compte ? <a href="register.php">Création de compte</a>
    </p>
  </div>
</body>
</html>
