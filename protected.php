
<?php
require_once 'Auth.php';

$pdo = new PDO("mysql:host=localhost;dbname=ma_base", "root", "");
$auth = new Auth($pdo);

$token = $_COOKIE['jwt'] ?? '';
$user = $auth->validateJWT($token);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Zone protégée</title>
</head>
<body>
<h2>Zone protégée</h2>

<?php if ($user): ?>
<p>Bienvenue, <?= htmlspecialchars($user->email) ?> !</p>
<p>Votre ID est : <?= htmlspecialchars($user->id) ?></p>

    <a onclick="logout()">Sortir</a>
    <script type="text/javascript">
        function logout(){
            localStorage.removeItem("jwt");
            document.cookie = "jwt=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            window.location.href = "index.php"
        }
    </script>
<?php else: ?>
<p>Accès refusé. Token invalide ou expiré.</p>
<a href="index.php">Retour à la connexion</a>

<?php endif; ?>

</body>
</html>