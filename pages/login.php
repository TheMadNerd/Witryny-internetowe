<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/User.php';

session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $user = User::login($email, $password);

    if ($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['name'] = $user->name;
        $_SESSION['role'] = $user->role_id;
        session_regenerate_id(true);
        header('Location: /index.php');
        exit;
    } else {
        $error = 'Nieprawidłowy email lub hasło.';
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie</title>
    <link rel="stylesheet" href="/css/login.css">
</head>
<body>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="login-wrapper">
    <div class="login-card">
        <h2>Zaloguj się</h2>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post" action="login.php">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Hasło:</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Zaloguj się</button>
        </form>

        <p class="register-link">
            Nie masz konta? <a href="register.php">Zarejestruj się</a>
        </p>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
