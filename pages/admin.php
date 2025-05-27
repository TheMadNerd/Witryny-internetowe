<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/User.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 3) {
    http_response_code(403);
    echo "Brak dostępu.";
    exit;
}

$pdo = Database::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user_id'])) {
    $idToDelete = (int)$_POST['delete_user_id'];
    if ($idToDelete !== $_SESSION['user_id']) {
        $stmt = $pdo->prepare("DELETE FROM Users WHERE user_id = :id");
        $stmt->execute(['id' => $idToDelete]);
    }
}

$users = $pdo->query("
    SELECT u.user_id, u.name, u.email, r.role_name
    FROM Users u
    JOIN Roles r ON u.role_id = r.role_id
    ORDER BY u.user_id
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <title>Panel administracyjny</title>
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body>
<div class="container">
    <h2>Użytkownicy</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Imię i nazwisko</th>
                <th>Email</th>
                <th>Rola</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= $u['user_id'] ?></td>
                    <td><?= htmlspecialchars($u['name']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['role_name']) ?></td>
                    <td>
                        <?php if ($u['user_id'] != $_SESSION['user_id']): ?>
                            <form method="post" onsubmit="return confirm('Na pewno usunąć tego użytkownika?');">
                                <input type="hidden" name="delete_user_id" value="<?= $u['user_id'] ?>">
                                <button type="submit">Usuń</button>
                            </form>
                        <?php else: ?>
                            (Twoje konto)
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
