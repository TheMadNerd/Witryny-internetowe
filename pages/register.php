<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/User.php';

session_start();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    $role = $_POST['role'] ?? 'student';

    if ($password !== $password2) {
        $error = 'Hasła nie są identyczne.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Nieprawidłowy adres email.';
    } else {
        $role_id = ($role === 'tutor') ? 2 : 1;
        $ok = User::register($name, $email, $password, $role_id);
        if ($ok) {
            $success = 'Rejestracja zakończona sukcesem. Możesz się teraz zalogować.';
        } else {
            $error = 'Nie udało się utworzyć konta. Adres email może być już zajęty.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <title>Rejestracja</title>
    <link rel="stylesheet" href="/css/register.css">
</head>
<body>
<div class="register-wrapper">
    <div class="register-card">
        <h2>Zarejestruj się</h2>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php elseif ($success): ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
        <p><a href="login.php">Przejdź do logowania</a></p>
    <?php endif; ?>

    <form method="post" action="register.php">
        <label for="name">Imię i nazwisko:</label><br>
        <input type="text" name="name" id="name" placeholder="Np. Jan Kowalski" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" name="email" id="email" placeholder="Np. jan.kowalski@example.com" required><br><br>

        <label for="password">Hasło:</label><br>
        <input type="password" name="password" id="password" placeholder="Wprowadź hasło" required><br><br>

        <label for="password2">Powtórz hasło:</label><br>
        <input type="password" name="password2" id="password2" placeholder="Powtórz hasło" required><br><br>

        <label for="role">Typ konta:</label><br>
        <select name="role" id="role" required>
            <option value="student">Uczeń</option>
            <option value="tutor">Korepetytor</option>
        </select><br><br>

        <button class="register-button" type="submit">Zarejestruj się</button>
    </form>

       <p class="login-link">
           Masz już konto? <a href="login.php">Zaloguj się</a>
       </p>
 </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
