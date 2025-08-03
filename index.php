<?php
require_once 'Auth.php';

$error= $token=false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $pdo = new PDO("mysql:host=localhost;dbname=ma_base", "root", "");

    $auth = new Auth($pdo);

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $token = $auth->login($email, $password);

    if ($token) {

    } else {
        $error=true;
        http_response_code(401);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <style>
        body { font-family: sans-serif; padding: 40px; }
        form { max-width: 400px; margin: auto; }
        input { display: block; width: 100%; margin: 10px 0; padding: 8px; }
        .error { color: red; }
        .token { background: #f3f3f3; padding: 10px; word-break: break-all; }
    </style>

    <script>
        function veriftoken() {
            const token = localStorage.getItem("jwt");

            if (token) {
                fetch("verifyToken.php", {
                    headers: {
                        "Authorization": "Bearer " + token
                    }
                })
                    .then(res => {
                        if (res.status === 200) {
                            window.location.href = "protected.php";
                        }
                    });
            }
        }
        veriftoken();
    </script>

</head>
<body>

<h2>Connexion</h2>

<form method="POST">
    <input type="email" name="email" placeholder="Email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    <input type="password" name="password" placeholder="Mot de passe" required>
    <input type="submit" value="Se connecter">
</form>

<?php if ($error): ?>
<p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if ($token): ?>


<p>
    <a href="protected.php">Accéder à une page protégée</a>

</p>

<script type="text/javascript">
       document.cookie = "jwt= <?php echo $token?> ; path=/; secure";
       localStorage.setItem("jwt","<?php echo $token?>")
       veriftoken();
</script>
<?php endif; ?>

</body>
</html>